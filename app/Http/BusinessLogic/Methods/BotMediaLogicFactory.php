<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotMethods;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\BotCollection;
use App\Http\Resources\BotMediaCollection;
use App\Http\Resources\BotMediaResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotMedia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotMediaLogicFactory
{
    protected $bot;
    protected $botUser;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;

    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser = null): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function list($filters = null, $search = null, $size = null): BotMediaCollection
    {

        $size = $size ?? config('app.results_per_page');

        $media = BotMedia::query();

        if (!is_null($this->bot))
            $media = $media->where("bot_id", $this->bot->id);

        if (!is_null($this->botUser))
            $media = $media->where("bot_user_id", $this->botUser->id);

        if (!is_null($search))
            $media = $media->where("caption", 'like', "%$search%");


        $func = is_null($filters)? function ($q) use ($filters) {
            foreach ($filters as $key => $value) {
                if (is_null($value))
                    continue;

                if (!$value)
                    continue;

                $q = $q->orWhere("type",$key);

                Log::info("$key");
            }
        } : null;


        if (!is_null($func))
            $media = $media->where($func);


        $media = $media
            ->orderBy("updated_at", 'DESC')
            ->paginate($size);

        return new BotMediaCollection($media);
    }

    /**
     * @throws HttpException
     */
    public function preview($mediaId): void
    {

        if (is_null($mediaId))
            throw new HttpException(404, "Медиа контент не найден!");

        $media = BotMedia::query()->find($mediaId);

        if (is_null($media))
            throw new HttpException(404, "Медиа контент не найден!");

        $bot = Bot::query()->find($media->bot_id);

        if (is_null($bot))
            throw new HttpException(404, "Бот не найден!");

        $action = BotMethods::bot()
            ->whereBot($bot);

        if ($media->type === "video" || $media->type === "video_note")
            $action->sendVideo(
                !is_null($this->botUser) ? $this->botUser->telegram_chat_id :
                    $bot->order_channel ?? $bot->main_channel ?? null,
                $media->caption ?? 'Описание не указано',
                $media->file_id, [
                    [
                        [
                            "text" => "Удалить видео", "callback_data" => "/remove_media $media->id"
                        ]
                    ]
                ]
            );

        if ($media->type === "photo")
            $action->sendPhoto(
                !is_null($this->botUser) ? $this->botUser->telegram_chat_id :
                    $bot->order_channel ?? $bot->main_channel ?? null,
                $media->caption ?? 'Описание не указано',
                $media->file_id, [
                    [
                        [
                            "text" => "Удалить фото", "callback_data" => "/remove_media $media->id"
                        ]
                    ]
                ]
            );


    }

    /**
     * @throws HttpException
     */
    public function destroy($mediaId): BotMediaResource
    {
        if (is_null($mediaId))
            throw new HttpException(404, "Медиа контент не найден!");

        $media = BotMedia::query()
            ->find($mediaId);

        if (is_null($media))
            throw new HttpException(404, "Медиа контент не найден!");

        $tmpMedia = $media;
        $media->delete();

        return new BotMediaResource($tmpMedia);
    }

}
