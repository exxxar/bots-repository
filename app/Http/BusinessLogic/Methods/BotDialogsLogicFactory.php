<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\BotDialogAnswerResource;
use App\Http\Resources\BotDialogCommandCollection;
use App\Http\Resources\BotDialogCommandResource;
use App\Http\Resources\BotDialogGroupCollection;
use App\Http\Resources\BotDialogGroupResource;
use App\Models\Bot;
use App\Models\BotDialogAnswer;
use App\Models\BotDialogCommand;
use App\Models\BotDialogGroup;
use App\Models\BotDialogResult;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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

    public function variablesList(): array
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return array_values(BotDialogCommand::query()
            ->where("bot_id", $this->bot->id)
            ->whereNotNull("use_result_as")
            ->get()
            ->pluck("use_result_as")
            ->toArray());
    }

    /**
     * @throws HttpException
     */
    public function getCommand($commandId): BotDialogCommandResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");


        $botDialogCommand = BotDialogCommand::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", $commandId)
            ->first();

        if (is_null($botDialogCommand))
            throw new HttpException(404, "Команда не найдена!");


        return new BotDialogCommandResource($botDialogCommand);
    }

    /**
     * @throws HttpException
     */

    public function commandList($search = null, $order = "id", $direction = "desc", $size = null): BotDialogCommandCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');


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
            ->orderBy($order, $direction)
            ->paginate($size);

        return new BotDialogCommandCollection($botDialogCommands);

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
                ->whereHas("botDialogCommands", function ($q) use ($search) {
                    $q->where("pre_text", 'like', "%$search%")
                        ->orWhere("slug", 'like', "%$search%")
                        ->orWhere("post_text", 'like', "%$search%");
                });

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
            //  'post_text' => "required",
            // 'error_text' => "required",
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

            $menu = json_decode($data["reply_keyboard"] ?? '[]');

            if (!empty($menu))
                $replyKeyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "reply",
                    'slug' => Str::uuid(),
                    'menu' => $this->recursiveMenuFix($menu)
                ]);
        }

        if (!is_null($data["inline_keyboard"] ?? null)) {

            $menu = json_decode($data["inline_keyboard"] ?? '[]');

            if (!empty($menu))
                $inlineKeyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "inline",
                    'slug' => Str::uuid(),
                    'menu' => $this->recursiveMenuFix($menu)
                ]);
        }

        $command = BotDialogCommand::query()->create([
            'slug' => Str::uuid(),
            'pre_text' => $data["pre_text"],
            'post_text' => $data["post_text"] ?? null,
            'error_text' => $data["error_text"] ?? "Ошибка",
            'bot_id' => $this->bot->id,
            'input_pattern' => $data["input_pattern"] ?? null,
            'inline_keyboard_id' => $data["inline_keyboard_id"] ?? $inlineKeyboard->id ?? null,
            'reply_keyboard_id' => $data["reply_keyboard_id"] ?? $replyKeyboard->id ?? null,
            'images' => $photos ?? [],
            'result_flags' => json_decode($data["result_flags"] ?? '[]'),
            'rules' => json_decode($data["rules"] ?? '[]'),
            'next_bot_dialog_command_id' => $data["next_bot_dialog_command_id"] ?? null,
            'bot_dialog_group_id' => $groupId,
            'is_empty' => ($data["is_empty"] ?? false) == "true" ? 1 : 0,
            'is_inform' => ($data["is_inform"] ?? false) == "true" ? 1 : 0,
            'result_channel' => $data["result_channel"] ?? null,
            'custom_stored_value' => $data["custom_stored_value"] ?? null,
            'use_result_as' => $data["use_result_as"] ?? null,
            'send_params' => json_decode($data["send_params"] ?? '[]'),
        ]);

        $answers = is_null($data["answers"] ?? null) ? null : json_decode($data["answers"] ?? '[]');

        if (is_null($answers))
            return new BotDialogCommandResource($command);

        foreach ($answers as $answer) {
            $isNextBotDialogCommand = !is_null(BotDialogCommand::query()->find($answer->next_bot_dialog_command_id ?? null));
            if (!$isNextBotDialogCommand) {
                $nextBotDialogCommand = BotDialogCommand::query()->create([
                    'slug' => Str::uuid(),
                    'pre_text' => $answer->next_bot_dialog_command_id ?? 'Текст диалога',
                    'post_text' => null,
                    'error_text' => "Ошибка",
                    'bot_id' => $this->bot->id,
                    'input_pattern' => null,
                    'inline_keyboard_id' => null,
                    'reply_keyboard_id' => null,
                    'images' => [],
                    'result_flags' => [],
                    'rules' => [],
                    'next_bot_dialog_command_id' => null,
                    'bot_dialog_group_id' => $groupId,
                    'is_empty' => false,
                    'is_inform' => false,
                    'custom_stored_value' => null,
                    'result_channel' => null,
                    'use_result_as' => null,
                    'send_params' => null,
                ]);

                $isNextBotDialogCommandId = $nextBotDialogCommand->id;
            } else
                $isNextBotDialogCommandId = $answer->next_bot_dialog_command_id;

            BotDialogAnswer::query()
                ->create([
                    'bot_dialog_command_id' => $command->id,
                    'answer' => $answer->answer ?? null,
                    'pattern' => $answer->pattern ?? null,
                    'custom_stored_value' => $answer->custom_stored_value ?? null,
                    'next_bot_dialog_command_id' => $isNextBotDialogCommandId,
                ]);
        }


        return new BotDialogCommandResource($command);
    }

    public function recursiveChain($commandId, &$refs)
    {
        $command = BotDialogCommand::query()
            ->where("id", $commandId)
            ->first();

        $refs = is_null($refs) ? [] : $refs;

        if (!in_array($commandId, $refs))
            $refs[] = $commandId;

        if (!is_null($command->next_bot_dialog_command_id)) {
            $refs[] = $command->next_bot_dialog_command_id;
            $this->recursiveChain($command->next_bot_dialog_command_id, $refs);
        }

    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function updateAnswer(array $data): BotDialogAnswerResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'id' => "required",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $tmp = (object)$data;

        $answer = BotDialogAnswer::query()->find($tmp->id);

        if (is_null($answer))
            throw new HttpException(404, "Ответ не найден!");

        $answer->update([
            'answer' => $tmp->answer ?? null,
            'pattern' => $tmp->pattern ?? null,
            'need_print' => $tmp->need_print ?? true,
            'custom_stored_value' => $tmp->custom_stored_value ?? null,
            'next_bot_dialog_command_id' => $tmp->next_bot_dialog_command_id ?? null,
        ]);

        return new BotDialogAnswerResource($answer);

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
            $menu = json_decode($data["reply_keyboard"] ?? '[]');

            if (!empty($menu))
                $replyKeyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "reply",
                    'slug' => Str::uuid(),
                    'menu' => $this->recursiveMenuFix($menu)

                ]);
        }

        if (!is_null($data["inline_keyboard"] ?? null)) {
            $menu = json_decode($data["inline_keyboard"] ?? '[]');

            if (!empty($menu))
                $inlineKeyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "inline",
                    'slug' => Str::uuid(),
                    'menu' => $this->recursiveMenuFix($menu)
                ]);
        }

        $tmp->post_text = $data["post_text"] ?? null;
        $tmp->inline_keyboard_id = $inlineKeyboard->id ?? $data["inline_keyboard_id"] ?? null;
        $tmp->reply_keyboard_id = $replyKeyboard->id ?? $data["reply_keyboard_id"] ?? null;
        $tmp->is_empty = ($data["is_empty"] ?? false) == "true" ? 1 : 0;
        $tmp->is_inform = ($data["is_inform"] ?? false) == "true" ? 1 : 0;
        $tmp->result_flags = json_decode($data["result_flags"] ?? '[]');
        $tmp->rules = json_decode($data["rules"] ?? '[]');
        $tmp->send_params = json_decode($data["send_params"] ?? '[]');
        $tmp->use_result_as = $data["use_result_as"] ?? null;
        $tmp->custom_stored_value = $data["custom_stored_value"] ?? null;

        $command = BotDialogCommand::query()->find($tmp->id);
        $command->update((array)$tmp);

        $answers = is_null($data["answers"] ?? null) ? null : json_decode($data["answers"] ?? '[]');

        if (is_null($answers))
            return new BotDialogCommandResource($command);

        foreach ($answers as $answer) {

            $isNextBotDialogCommand = !is_null(BotDialogCommand::query()->find($answer->next_bot_dialog_command_id ?? null));

            if (!$isNextBotDialogCommand) {
                $nextBotDialogCommand = BotDialogCommand::query()->create([
                    'slug' => Str::uuid(),
                    'pre_text' => $answer->next_bot_dialog_command_id ?? 'Текст диалога',
                    'post_text' => null,
                    'error_text' => "Ошибка",
                    'bot_id' => $this->bot->id,
                    'input_pattern' => null,
                    'inline_keyboard_id' => null,
                    'reply_keyboard_id' => null,
                    'images' => [],
                    'result_flags' => [],
                    'rules' => [],
                    'next_bot_dialog_command_id' => null,
                    'bot_dialog_group_id' => $data["bot_dialog_group_id"] ?? null,
                    'is_empty' => false,
                    'is_inform' => false,
                    'custom_stored_value' => null,
                    'result_channel' => null,
                    'use_result_as' => null,
                    'send_params' => null,
                ]);

                $isNextBotDialogCommandId = $nextBotDialogCommand->id;
            } else
                $isNextBotDialogCommandId = $answer->next_bot_dialog_command_id;

            $tmp = [
                'bot_dialog_command_id' => $command->id,
                'answer' => $answer->answer ?? null,
                'pattern' => $answer->pattern ?? null,
                'custom_stored_value' => $answer->custom_stored_value ?? null,
                'next_bot_dialog_command_id' => $isNextBotDialogCommandId,
            ];

            if (!is_null($answer->id ?? null)) {
                $answer = BotDialogAnswer::query()->find($answer->id);
                $answer->update($tmp);
            } else
                BotDialogAnswer::query()
                    ->create($tmp);
        }


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

    public function removeDialogAnswer($answerId): BotDialogAnswerResource
    {
        $answer = BotDialogAnswer::query()->find($answerId);

        if (is_null($answer))
            throw new HttpException(404, "Ответ не найден!");

        $tmpAnswer = $answer;

        $answer->bot_dialog_command_id = null;
        $answer->next_bot_dialog_command_id = null;
        $answer->save();

        $answer->delete();

        return new BotDialogAnswerResource($tmpAnswer);
    }

    public function removeDialog($dialogId): BotDialogCommandResource
    {
        $command = BotDialogCommand::query()->find($dialogId);

        if (is_null($command))
            throw new HttpException(404, "Диалоговая команда не найден!");

        $tmpCommand = $command;

        $command->inline_keyboard_id = null;
        $command->reply_keyboard_id = null;
        $command->next_bot_dialog_command_id = null;
        $command->bot_dialog_group_id = null;

        if (count($command->answers ?? []) > 0) {
            foreach ($command->answers as $answer) {

                $answer->bot_dialog_command_id = null;
                $answer->next_bot_dialog_command_id = null;
                $answer->delete();

            }
        }

        $command->save();

        Schema::disableForeignKeyConstraints();
        $command->forceDelete();
        Schema::enableForeignKeyConstraints();

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
