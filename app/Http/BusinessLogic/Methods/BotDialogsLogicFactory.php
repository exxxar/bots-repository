<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\BotDialogCommandCollection;
use App\Http\Resources\BotDialogCommandResource;
use App\Http\Resources\BotDialogGroupCollection;
use App\Http\Resources\BotDialogGroupResource;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotDialogGroup;
use App\Models\BotDialogResult;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotDialogsLogicFactory
{
    use LogicUtilities;


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

    public function list($search = null, $size = null, bool $simple = false): BotDialogCommandCollection|BotDialogGroupCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        if ($simple) {


            $botDialogCommands = BotDialogCommand::query()
                ->where("bot_id", $this->bot->id);

            if (!is_null($search))
                $botDialogCommands = $botDialogCommands
                    ->where(function ($q) use ($search) {
                        $q->where("pre_text", 'like', "%$search%")
                            ->orWhere("slug", 'like', "%$search%")
                            ->orWhere("post_text", 'like', "%$search%");

                    });

            $botDialogCommands = $botDialogCommands
                ->orderBy("created_at", "desc")
                ->paginate($size);

            return new BotDialogCommandCollection($botDialogCommands);
        }

        $botDialogGroups = BotDialogGroup::query()
            ->where("bot_id", $this->bot->id);


        if (!is_null($search))
            $botDialogGroups = $botDialogGroups
                ->where("slug", 'like', "%$search%");

        $botDialogGroups = $botDialogGroups
            ->orderBy("created_at", "desc")
            ->paginate($size);


        return new BotDialogGroupCollection($botDialogGroups);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function swapGroup(array $data): void
    {
        $validator = Validator::make($data, [
            "dialogGroupId" => "required",
            "dialogCommandId" => "required"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $group = BotDialogGroup::query()->find($data["dialogGroupId"]);

        if (is_null($group))
            throw new HttpException(404, "Диалоговая группа не найдена!");

        $command = BotDialogCommand::query()->find($data["dialogCommandId"]);

        if (is_null($command))
            throw new HttpException(404, "Диалоговая команда не найдена!");

        $command->bot_dialog_group_id = $group->id;
        $command->save();

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function swapDialog(array $data): void
    {
        $validator = Validator::make($data, [
            "dialogCommandToId" => "required",
            "dialogCommandFromId" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $command1 = BotDialogCommand::query()
            ->find($data["dialogCommandToId"]);

        $command2 = BotDialogCommand::query()
            ->find($data["dialogCommandFromId"]);

        if (is_null($command1) || is_null($command2))
            throw new HttpException(404, "Диалоговая команда не найдена!");

        $command1->next_bot_dialog_command_id = $command2->id;
        $command1->save();
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function unlinkDialog(array $data): void
    {
        $validator = Validator::make($data, [
            "dialogCommandId" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $command = BotDialogCommand::query()
            ->find($data["dialogCommandId"]);


        if (is_null($command))
            throw new HttpException(404, "Диалоговая команда не найдена!");

        $command->next_bot_dialog_command_id = null;
        $command->save();
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function duplicateDialog(array $data): BotDialogCommandResource
    {
        $validator = Validator::make($data, [
            "dialogCommandId" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $command = BotDialogCommand::query()
            ->find($data["dialogCommandId"]);

        if (is_null($command))
            throw new HttpException(404, "Диалоговая команда не найдена!");

        $command = $command->replicate();
        $command->next_bot_dialog_command_id = null;
        $command->save();

        return new BotDialogCommandResource($command);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function createGroup(array $data): BotDialogGroupResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'slug' => "required",
            'title' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $group = BotDialogGroup::query()->create([
            'slug' => $data["slug"],
            'title' => $data["title"],
            'bot_id' => $this->bot->id,
        ]);

        return new BotDialogGroupResource($group);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function createDialog(array $data, array $files = null): BotDialogCommandResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'pre_text' => "required",
            'post_text' => "required",
            'error_text' => "required",
            'bot_id' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'reply_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",
            'result_channel' => ""
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $slug = $this->bot->company->slug;

        $photos = $this->uploadPhotos("/public/companies/" . $slug, $files);

        $baseGroup = BotDialogGroup::query()
            ->where("slug", "default_bot_group_slug")
            ->where("bot_id", $this->bot->id)
            ->first();

        $groupId = $data["bot_dialog_group_id"] ?? $baseGroup->id ?? null;

        if (is_null($groupId)) {
            $baseGroup = BotDialogGroup::query()->create([
                'slug' => "default_bot_group_slug",
                'title' => "Группа по умолчанию",
                'bot_id' => $this->bot->id
            ]);

            $groupId = $baseGroup->id;
        }

        if (!is_null($data["reply_keyboard"] ?? null)) {
            $replyKeyboard = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "reply",
                'slug' => Str::uuid(),
                'menu' => json_decode($data["reply_keyboard"] ?? '[]')
            ]);
        }

        if (!is_null($data["inline_keyboard"] ?? null)) {
            $inlineKeyboard = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "inline",
                'slug' => Str::uuid(),
                'menu' => json_decode($data["inline_keyboard"] ?? '[]')
            ]);
        }

        $command = BotDialogCommand::query()->create([
            'slug' => Str::uuid(),
            'pre_text' => $data["pre_text"],
            'post_text' => $data["post_text"],
            'error_text' => $data["error_text"],
            'bot_id' => $this->bot->id,
            'input_pattern' => $data["input_pattern"] ?? null,
            'inline_keyboard_id' => $data["inline_keyboard_id"] ?? $inlineKeyboard->id ?? null,
            'reply_keyboard_id' => $data["reply_keyboard_id"] ?? $replyKeyboard->id ?? null,
            'images' => $photos ?? [],
            'next_bot_dialog_command_id' => $data["next_bot_dialog_command_id"] ?? null,
            'bot_dialog_group_id' => $groupId,
            'is_empty' =>($data["is_empty"] ?? false) == "true" ? 1 : 0,
            'result_channel' => $data["result_channel"] ?? null,
        ]);



        return new BotDialogCommandResource($command);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function updateDialog(array $data, array $files = null): BotDialogCommandResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'id' => "required",
            'slug' => "required",
            'pre_text' => "required",
            'post_text' => "required",
            'error_text' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'reply_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",
            'bot_dialog_group_id' => "required",
            'result_channel' => ""
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $slug = $this->bot->company->slug;
        $photos = $this->uploadPhotos("/public/companies/" . $slug, $files);

        $tmp = (object)$data;

        $tmp->images = json_decode($tmp->images ?? '[]');

        if (count($photos) > 0)
            $tmp->images = $photos;

        if (!is_null($data["reply_keyboard"] ?? null)) {
            $replyKeyboard = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "reply",
                'slug' => Str::uuid(),
                'menu' => json_decode($data["reply_keyboard"] ?? '[]')
            ]);
        }

        if (!is_null($data["inline_keyboard"] ?? null)) {
            $inlineKeyboard = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "inline",
                'slug' => Str::uuid(),
                'menu' => json_decode($data["inline_keyboard"] ?? '[]')
            ]);
        }

        $tmp->inline_keyboard_id = $inlineKeyboard->id ?? $data["inline_keyboard_id"] ?? null;
        $tmp->reply_keyboard_id = $replyKeyboard->id ?? $data["reply_keyboard_id"] ?? null;
        $tmp->is_empty = ($data["is_empty"] ?? false) == "true" ? 1 : 0;

        $command = BotDialogCommand::query()->find($tmp->id);
        $command->update((array)$tmp);

        return new BotDialogCommandResource($command);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function updateGroup(array $data): BotDialogGroupResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "id" => "required",
            "slug" => "required",
            "title" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $group = BotDialogGroup::query()->find($data["id"]);

        if (is_null($group))
            throw new HttpException(404, "Диалоговая группа не найден!");

        $group->update($data);

        return new BotDialogGroupResource($group);
    }

    /**
     * @throws HttpException
     */
    public function removeGroup($groupId): BotDialogGroupResource
    {
        $group = BotDialogGroup::query()
            ->find($groupId);

        if (is_null($group))
            throw new HttpException(404, "Диалоговая группа не найден!");

        $baseGroup = BotDialogGroup::query()
            ->where("slug", "default_bot_group_slug")
            ->where("bot_id", $group->bot_id)
            ->first();

        if (is_null($baseGroup)) {
            $baseGroup = BotDialogGroup::query()->create([
                'slug' => "default_bot_group_slug",
                'title' => "Группа по умолчанию",
                'bot_id' => $group->bot_id
            ]);
        }

        foreach (($group->botDialogCommands ?? []) as $command) {
            $command->bot_dialog_group_id = $baseGroup->id;
            $command->save();
        }

        $tmpGroup = $group;

        $group->delete();

        return new BotDialogGroupResource($tmpGroup);
    }

    public function removeDialog($dialogId): BotDialogCommandResource
    {
        $command = BotDialogCommand::query()->find($dialogId);

        if (is_null($command))
            throw new HttpException(404, "Диалоговая команда не найден!");

        $tmpCommand = $command;
        $command->delete();

        return new BotDialogCommandResource($tmpCommand);
    }

    public function stopDialogs(): void
    {

        $botUsers = BotUser::query()
            ->get();

        foreach ($botUsers as $botUser) {
            $botUser->in_dialog_mode = false;
            $botUser->save();

            $results = BotDialogResult::query()
                ->whereNull("completed_at")
                ->where("bot_user_id", $botUser->id)
                ->get();

            if (count($results) > 0)
                foreach ($results as $result) {
                    $result->completed_at = Carbon::now();
                    $result->save();
                }


        }

    }

}
