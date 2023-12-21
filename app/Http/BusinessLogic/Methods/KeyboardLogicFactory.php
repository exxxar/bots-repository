<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\BotMenuTemplateCollection;
use App\Http\Resources\BotMenuTemplateResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class KeyboardLogicFactory
{
    protected $bot;

    public function __construct()
    {
        $this->bot = null;

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
    public function list(): BotMenuTemplateCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Условия функции не выполнены!");

        $keyboards = BotMenuTemplate::query()
            ->where("bot_id", $this->bot->id)
            ->orderBy("updated_at", "desc")
            ->get();

        return new BotMenuTemplateCollection($keyboards);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function create(array $data): BotMenuTemplateResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            "slug" => "required",
            "menu" => "required",
            "type" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $botMenuTemplate = BotMenuTemplate::query()
            ->create([
                "slug" => $request->slug ?? Str::uuid(),
                "menu" => json_decode($data["menu"]),
                "type" => $data["type"],
                "bot_id" => $this->bot->id,
            ]);

        return new BotMenuTemplateResource($botMenuTemplate);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function update(array $data): BotMenuTemplateResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            "id" => "required",
            "slug" => "required",
            "menu" => "required",
            "type" => "required",
            "bot_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $botMenuTemplate = BotMenuTemplate::query()
            ->find($data["id"]);

        if (is_null($botMenuTemplate))
            throw new HttpException(404, "Клавиатура не найдена");

        $menu = json_decode($data["menu"]);


        if (isset($menu->menu))
            $menu = $menu->menu;



       $botMenuTemplate
            ->update([
                "slug" => $data["slug"] ?? Str::uuid(),
                "menu" => $menu,
                "type" => $data["type"],
                "bot_id" => $this->bot->id,
            ]);


        return new BotMenuTemplateResource($botMenuTemplate);
    }

    /**
     * @throws HttpException
     */
    public function destroy($keyboardId): BotMenuTemplateResource
    {
        $botMenuTemplate = BotMenuTemplate::query()->find($keyboardId);

        if (is_null($botMenuTemplate))
            throw new HttpException(404, "Клавиатура не найдена");

        $pages = BotPage::query()
            ->where('reply_keyboard_id', $keyboardId)
            ->orWhere('inline_keyboard_id', $keyboardId)
            ->get();

        foreach ($pages as $page) {
            if ($page->reply_keyboard_id == $keyboardId)
                $page->reply_keyboard_id = null;
            if ($page->inline_keyboard_id == $keyboardId)
                $page->inline_keyboard_id = null;
            $page->save();
        }

        $tmpKeyboard = $botMenuTemplate;
        $botMenuTemplate->delete();

        return new BotMenuTemplateResource($tmpKeyboard);
    }


}
