<?php

namespace App\Http\Controllers\Bots\Web;


use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Bot;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{

    public function reviewList(Request $request, $eventId): \App\Http\Resources\AppointmentReviewCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->reviewList(
                $eventId,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    public function eventList(Request $request): \App\Http\Resources\AppointmentEventCollection
    {
        return BusinessLogic::appointment()
            ->setBot($request->bot ?? null)
            ->eventList($request->search ?? null,
                $request->is_group ?? false,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }


    public function scheduleList(Request $request, $eventId): \App\Http\Resources\AppointmentScheduleCollection
    {

        return BusinessLogic::appointment()
            ->setBot($request->bot ?? null)
            ->scheduleList($eventId,
                date: $request->date ?? null,
                order: $request->order ?? null,
                direction: $request->direction ?? null
            );
    }

    public function serviceCategoryList(Request $request, $eventId): \Illuminate\Http\JsonResponse
    {

        return response()->json(BusinessLogic::appointment()
            ->setBot($request->bot ?? null)
            ->serviceCategoryList($eventId));
    }


    public function appointmentList(Request $request, $eventId = null): AppointmentCollection
    {


        return BusinessLogic::appointment()
            ->setBot($request->bot ?? null)
            ->appointmentList($eventId, $request->status ?? 0);
    }


    public function serviceList(Request $request, $eventId): \App\Http\Resources\AppointmentServiceCollection
    {

        return BusinessLogic::appointment()
            ->setBot($request->bot ?? null)
            ->serviceList(
                $eventId,
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    /**
     * @throws ValidationException
     */
    public function storeAppointment(Request $request): AppointmentResource
    {
        $request->validate([
            'appointment_event_id' => "required",
            'appointment_schedule_id' => "required",
        ]);


        return BusinessLogic::appointment()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->storeAppointment($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function storeReview(Request $request): \App\Http\Resources\AppointmentReviewResource
    {
        $request->validate([
            "bot_id" => "required",
            "bot_user_id" => "required",
            'appointment_event_id' => "required",
            'appointment_schedule_id' => "required",
            'rating' => "required",
            'text' => "required",

        ]);

        $bot = Bot::query()
            ->find($request->bot_id ?? $request->botId ?? null);

        $botUser = BotUser::query()
            ->find($request->bot_user_id ?? $request->botUser->id ?? null);

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->storeReview($request->all());
    }


    /**
     * @throws ValidationException
     */
    public function addEvent(Request $request): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id" => "required",
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

    /**
     * @throws ValidationException
     */
    public function storeSchedule(Request $request): \App\Http\Resources\AppointmentScheduleCollection
    {
        $request->validate([
            "bot_id" => "required",
            'appointment_event_id' => "required",
            'schedule' => "required|array",
            'schedule.*.start_time' => "required",
            'schedule.*.day' => "required",
            'schedule.*.week' => "required",
            'schedule.*.year' => "required",
            'schedule.*.month' => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? $request->botId ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->storeSchedule($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function addService(Request $request): \App\Http\Resources\AppointmentServiceResource
    {
        $request->validate([
            "bot_id" => "required",
            'appointment_event_id' => "required",
            'title' => "required",
            'description' => "required",
            'category' => "required",
            'images' => "required",
            'price' => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? $request->botId ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->addService($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function updateService(Request $request): \App\Http\Resources\AppointmentServiceResource
    {
        $request->validate([
            "bot_id" => "required",
            'appointment_event_id' => "required",
            'title' => "required",
            'description' => "required",
            'category' => "required",
            'images' => "required",
            'price' => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? $request->botId ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->updateService($request->all());
    }


    public function duplicateEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
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
            "bot_id" => "required",
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


    public function removeService(Request $request, $serviceId): \App\Http\Resources\AppointmentServiceResource
    {

        return BusinessLogic::appointment()
            ->removeService($serviceId);
    }

    public function removeReview(Request $request, $reviewId): \App\Http\Resources\AppointmentReviewResource
    {

        return BusinessLogic::appointment()
            ->removeReview($reviewId);
    }


    public function removeSchedule(Request $request, $scheduleId): \App\Http\Resources\AppointmentScheduleResource
    {


        return BusinessLogic::appointment()
            ->removeSchedule($scheduleId);
    }

    public function removeEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {


        return BusinessLogic::appointment()
            ->removeEvent($eventId);
    }

    public function forceRemoveEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {


        return BusinessLogic::appointment()
            ->removeEvent($eventId, true);
    }

    public function restoreEvent(Request $request, $eventId): \App\Http\Resources\AppointmentEventResource
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::appointment()
            ->setBot($bot ?? null)
            ->restoreEvent($eventId);
    }

}
