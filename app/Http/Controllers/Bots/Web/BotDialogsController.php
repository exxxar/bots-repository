<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotDialogCommandResource;
use App\Http\Resources\BotDialogGroupCollection;
use App\Http\Resources\BotDialogGroupResource;
use App\Models\Bot;
use App\Models\BotDialogGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BotDialogsController extends Controller
{
    public function index(Request $request): BotDialogGroupCollection
    {

        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
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
            ->setBot($request->bot ?? null)
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
            ->setBot($request->bot ?? null)
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
            ->setBot($request->bot ?? null)
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
            ->setBot($request->bot ?? null)
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

        ]);

        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
            ->createGroup($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function addDialog(Request $request): BotDialogCommandResource
    {
        $request->validate([
            'pre_text' => "required",
            'post_text' => "required",
            'error_text' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",
            'result_channel' => ""
        ]);


        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
            ->createDialog($request->all(),
                $request->hasFile('files') ?
                    $request->file('files') : null);
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
            'post_text' => "required",
            'error_text' => "required",
            'input_pattern' => "",
            'inline_keyboard_id' => "",
            'images' => "",
            'next_bot_dialog_command_id' => "",
            'bot_dialog_group_id' => "required",
            'result_channel' => ""
        ]);


        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
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

        ]);

        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
            ->updateGroup($request->all());
    }

    public function removeGroup(Request $request, $groupId): BotDialogGroupResource
    {
        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
            ->removeGroup($groupId);
    }

    public function removeDialog(Request $request, $dialogId): BotDialogCommandResource
    {
        return BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
            ->removeDialog($dialogId);
    }

    public function stopDialogs(Request $request): Response
    {
        BusinessLogic::dialogs()
            ->setBot($request->bot ?? null)
            ->stopDialogs();

        return response()->noContent();
    }

}
