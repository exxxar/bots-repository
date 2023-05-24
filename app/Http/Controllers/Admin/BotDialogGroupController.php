<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotDialogGroupStoreRequest;
use App\Http\Requests\BotDialogGroupUpdateRequest;
use App\Http\Resources\BotDialogCommandResource;
use App\Http\Resources\BotDialogGroupCollection;
use App\Http\Resources\BotDialogGroupResource;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotDialogGroup;
use App\Models\BotDialogResult;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BotDialogGroupController extends Controller
{
    public function index(Request $request)
    {

        $botId = $request->botId ?? null;

        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;

        $botDialogGroups = BotDialogGroup::query();

        if (!is_null($botId))
            $botDialogGroups = $botDialogGroups->where("bot_id", $botId);

        if (!is_null($search))
            $botDialogGroups = $botDialogGroups
                ->where("slug", 'like', "%$search%");

        $botDialogGroups = $botDialogGroups
            ->orderBy("created_at","desc")
            ->paginate($size);


        return BotDialogGroupResource::collection($botDialogGroups);
    }

    public function swapGroup(Request $request)
    {
        $request->validate([
            "dialogGroupId" => "required",
            "dialogCommandId" => "required"


        ]);

        $group = BotDialogGroup::query()->find($request->dialogGroupId);

        if (is_null($group))
            return response()->noContent(404);

        $command = BotDialogCommand::query()->find($request->dialogCommandId);

        if (is_null($command))
            return response()->noContent(404);


        $command->bot_dialog_group_id = $group->id;
        $command->save();

        return response()->noContent();

    }

    public function swapDialog(Request $request)
    {
        $request->validate([
            "dialogCommandToId" => "required",
            "dialogCommandFromId" => "required",
        ]);


        $command1 = BotDialogCommand::query()
            ->find($request->dialogCommandToId);

        $command2 = BotDialogCommand::query()
            ->find($request->dialogCommandFromId);

        if (is_null($command1) || is_null($command2))
            return response()->noContent(404);

        $command1->next_bot_dialog_command_id = $command2->id;
        $command1->save();

        return response()->noContent();
    }

    public function attachDialogToSlug(Request $request)
    {
        $request->validate([
            "dialogCommandId" => "required",
            "slugId" => "required",
        ]);


        $command = BotDialogCommand::query()
            ->find($request->dialogCommandId);

        $slug = BotMenuSlug::query()
            ->find($request->slugId);

        if (is_null($command) || is_null($slug))
            return response()->noContent(404);

        $slug->bot_dialog_command_id = $command->id;
        $slug->save();

        return response()->noContent();
    }

    public function unlinkDialog(Request $request)
    {
        $request->validate([
            "dialogCommandId" => "required",
        ]);



        $command = BotDialogCommand::query()
            ->find($request->dialogCommandId);

        if (is_null($command))
            return response()->noContent(404);

        $command->next_bot_dialog_command_id = null;
        $command->save();

        return response()->noContent();
    }

    public function duplicateDialog(Request $request)
    {
        $request->validate([
            "dialogCommandId" => "required",
        ]);

        $command = BotDialogCommand::query()
            ->find($request->dialogCommandId);

        if (is_null($command))
            return response()->noContent(404);

        $command = $command->replicate();
        $command->next_bot_dialog_command_id = null;
        $command->save();

        return response()->noContent();
    }

    public function addGroup(Request $request)
    {
        $request->validate([
            'slug' => "required",
            'title' => "required",
            'bot_id' => "required",
        ]);

        $group = BotDialogGroup::query()->create([
            'slug' => $request->slug,
            'title' => $request->title,
            'bot_id' => $request->bot_id,
        ]);

        return new BotDialogGroupResource($group);
    }

    public function addDialog(Request $request)
    {
        $request->validate([
            'slug' => "required",
            'pre_text' => "required",
            'post_text' => "required",
            'error_text' => "required",
            'bot_id' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",

            'result_channel' => ""
        ]);

        $photos = [];

        if ($request->hasFile('files')) {

            $bot = Bot::query()
                ->with(["company"])
                ->find($request->bot_id);

            $files = $request->file('files');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$bot->company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }


        $baseGroup = BotDialogGroup::query()
            ->where("slug", "default_bot_group_slug")
            ->where("bot_id", $request->bot_id)
            ->first();

        $groupId = $request->bot_dialog_group_id ?? $baseGroup->id ?? null;

        if (is_null($groupId)) {
            $baseGroup = BotDialogGroup::query()->create([
                'slug' => "default_bot_group_slug",
                'title' => "Группа по умолчанию",
                'bot_id' => $request->bot_id
            ]);

            $groupId = $baseGroup->id;
        }



        $command = BotDialogCommand::query()->create([
            'slug' => $request->slug,
            'pre_text' => $request->pre_text,
            'post_text' => $request->post_text,
            'error_text' => $request->error_text,
            'bot_id' => $request->bot_id,
            'input_pattern' => $request->input_pattern ?? null,
            'inline_keyboard_id' => $request->inline_keyboard_id ?? null,
            'images' => $photos ?? [],
            'next_bot_dialog_command_id' => $request->next_bot_dialog_command_id ?? null,
            'bot_dialog_group_id' => $groupId,
            'result_channel' => $request->result_channel ?? null,
        ]);

        return new BotDialogCommandResource($command);
    }

    public function updateDialog(Request $request)
    {
        $request->validate([
            'id' => "required",
            'slug' => "required",
            'pre_text' => "required",
            'post_text' => "required",
            'error_text' => "required",
            'bot_id' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",
            'bot_dialog_group_id' => "required",
            'result_channel' => ""
        ]);

        $photos = [];

        if ($request->hasFile('files')) {

            $bot = Bot::query()
                ->with(["company"])
                ->find($request->bot_id);

            $files = $request->file('files');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$bot->company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $tmp = (object)$request->all();

        $tmp->images = json_decode($tmp->images ?? '[]');

        if (count($photos) > 0)
            $tmp->images = $photos;

        $command = BotDialogCommand::query()->find($request->id);
        $command->update((array)$tmp);

        return new BotDialogCommandResource($command);
    }

    public function updateGroup(Request $request)
    {
        $request->validate([
            "id" => "required",
            "slug" => "required",
            "title" => "required",
            "bot_id" => "required",

        ]);

        $group = BotDialogGroup::query()->find($request->id);

        if (is_null($group))
            return response()->noContent(404);

        $group->update($request->all());

        return response()->noContent();
    }

    public function removeGroup(Request $request, $groupId)
    {
        $group = BotDialogGroup::query()->find($groupId);

        if (is_null($group))
            return response()->noContent(404);

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

        foreach ($group->botDialogCommands as $command) {
            $command->bot_dialog_group_id = $baseGroup->id;
            $command->save();
        }

        $group->delete();

        return response()->noContent();
    }

    public function removeDialog(Request $request, $dialogId)
    {
        $command = BotDialogCommand::query()->find($dialogId);

        if (is_null($command))
            return response()->noContent(404);

        $command->delete();

        return response()->noContent();
    }

    public function stopDialogs(Request $request)
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

          /*  BotMethods::bot()
                ->whereId($botUser->bot_id)
                ->sendMessage($botUser->telegram_chat_id,
                    "Ваш диалог принудительно остановлен администратором системы!");*/

        }

        return response()->noContent();
    }

}
