<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotDialogGroupStoreRequest;
use App\Http\Requests\BotDialogGroupUpdateRequest;
use App\Http\Resources\BotDialogCommandCollection;
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
use Illuminate\Validation\ValidationException;

class BotDialogGroupController extends Controller
{

    public function variablesList(Request $request): \Illuminate\Http\JsonResponse
    {
        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->botId ?? $request->bot_id ?? null)
            ->first();

        return response()->json(BusinessLogic::dialogs()
            ->setBot($bot)
            ->variablesList());
    }

    public function getCommand(Request $request, $commandId): BotDialogCommandResource
    {
        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->botId ?? $request->bot_id ?? null)
            ->first();

        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->getCommand($commandId);
    }

    public function commandList(Request $request): BotDialogCommandCollection
    {
        $bot = Bot::query()
            ->where("id", $request->botId ?? $request->bot_id ?? null)
            ->first();

        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->commandList(
                $request->search ?? null,
                $request->order ?? "id",
                $request->direction ?? "desc",
                $request->get("size") ?? config('app.results_per_page'),

            );
    }

    public function index(Request $request): BotDialogGroupCollection
    {
        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->botId ?? $request->bot_id ?? null)
            ->first();


        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->list(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),

            );
    }

    /**
     * @throws ValidationException
     */
    public function swapGroup(Request $request): Response
    {
        $request->validate([
            "dialogGroupId" => "required",
            "dialogCommandId" => "required"
        ]);

        BusinessLogic::dialogs()
            ->swapGroup($request->all());

        return response()->noContent();

    }

    /**
     * @throws ValidationException
     */
    public function swapDialog(Request $request): Response
    {
        $request->validate([
            "dialogCommandToId" => "required",
            "dialogCommandFromId" => "required",
        ]);

        BusinessLogic::dialogs()
            ->swapDialog($request->all());

        return response()->noContent();
    }


    /**
     * @throws ValidationException
     */
    public function unlinkDialog(Request $request): Response
    {
        $request->validate([
            "dialogCommandId" => "required",
        ]);

        BusinessLogic::dialogs()
            ->unlinkDialog($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function duplicateDialog(Request $request): BotDialogCommandResource
    {
        $request->validate([
            "dialogCommandId" => "required",
        ]);

        return BusinessLogic::dialogs()
            ->duplicateDialog($request->all());

    }

    /**
     * @throws ValidationException
     */
    public function addGroup(Request $request): BotDialogGroupResource
    {
        $request->validate([
            'slug' => "required",
            'title' => "required",
            'bot_id' => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->createGroup($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function addDialog(Request $request): BotDialogCommandResource
    {
        $request->validate([
            'pre_text' => "required",
           // 'post_text' => "required",
           // 'error_text' => "required",
            'bot_id' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",
            'result_channel' => ""
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();


        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->createDialog($request->all(),
                $request->hasFile('files') ?
                    $request->file('files') : null);
    }

    /**
     * @throws ValidationException
     */
    public function updateAnswer(Request $request): \App\Http\Resources\BotDialogAnswerResource
    {
        $request->validate([
            'id' => "required",
            'bot_id' => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->updateAnswer($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function updateDialog(Request $request): BotDialogCommandResource
    {
        $request->validate([
            'id' => "required",
            'slug' => "required",
            'pre_text' => "required",
            'bot_id' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'images' => "",
            'videos' => "",
            'documents' => "",
            'next_bot_dialog_command_id' => "",
            'bot_dialog_group_id' => "required",
            'result_channel' => ""
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->updateDialog($request->all(),
                $request->hasFile('files') ?
                    $request->file('files') : null);
    }

    /**
     * @throws ValidationException
     */
    public function updateGroup(Request $request): BotDialogGroupResource
    {
        $request->validate([
            "id" => "required",
            "slug" => "required",
            "title" => "required",
            "bot_id" => "required",

        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::dialogs()
            ->setBot($bot)
            ->updateGroup($request->all());
    }

    public function removeGroup(Request $request, $groupId): BotDialogGroupResource
    {
        return BusinessLogic::dialogs()
            ->removeGroup($groupId);
    }


    public function removeDialogAnswer(Request $request, $answerId): \App\Http\Resources\BotDialogAnswerResource
    {
        return BusinessLogic::dialogs()
            ->removeDialogAnswer($answerId);
    }

    public function removeDialog(Request $request, $dialogId): BotDialogCommandResource
    {
        return BusinessLogic::dialogs()
            ->removeDialog($dialogId);
    }

    public function stopDialogs(Request $request): Response
    {
        BusinessLogic::dialogs()
            ->stopDialogs();

        return response()->noContent();
    }

}
