<?php

namespace App\Http\Controllers\Admin;


use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function eventList(Request $request): \App\Http\Resources\AppointmentEventCollection
    {
        $request->validate([
            "bot_id"=>"required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id  ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->eventList($request->search ?? null,
                $request->is_group ?? false,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    /**
     * @throws ValidationException
     */
    public function addEvent(Request $request): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id"=>"required",
            'title' => "required",
            'subtitle' => "required",
            'description' => "required",
            'images' => "required",
            //'is_group',
            // 'max_people',
            //'mix_people',
            'on_start_appointment' => "required",
            'on_cancel_appointment' => "required",
            'on_after_appointment' => "required",
            'on_repeat_appointment' => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? $request->botId ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->addEvent($request->all()
            );
    }


    public function duplicateEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id"=>"required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id  ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->duplicateEvent($eventId);
    }

    /**
     * @throws ValidationException
     */
    public function updateEvent(Request $request): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id"=>"required",
            'title' => "required",
            'subtitle' => "required",
            'description' => "required",
            'images' => "required",
            //'is_group',
            // 'max_people',
            //'mix_people',
            'on_start_appointment' => "required",
            'on_cancel_appointment' => "required",
            'on_after_appointment' => "required",
            'on_repeat_appointment' => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? $request->botId ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->updateEvent($request->all());
    }

    public function removeEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id"=>"required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id  ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->removeEvent($eventId);
    }

    public function forceRemoveEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id"=>"required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id  ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->removeEvent($eventId, true);
    }

    public function restoreEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id"=>"required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id  ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->restoreEvent($eventId);
    }

}
