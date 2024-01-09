<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\AppointmentStatusEnum;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentEventCollection;
use App\Http\Resources\AppointmentEventResource;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentReviewCollection;
use App\Http\Resources\AppointmentReviewResource;
use App\Http\Resources\AppointmentScheduleCollection;
use App\Http\Resources\AppointmentScheduleResource;
use App\Http\Resources\AppointmentServiceCollection;
use App\Http\Resources\AppointmentServiceResource;
use App\Http\Resources\BotCollection;
use App\Http\Resources\BotPageResource;
use App\Models\AmoCrm;
use App\Models\Appointment;
use App\Models\AppointmentEvent;
use App\Models\AppointmentReview;
use App\Models\AppointmentSchedule;
use App\Models\AppointmentService;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppointmentLogicFactory
{
    use LogicUtilities;

    protected $bot;

    protected $botUser;

    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;
    }

    /**
     * @throws HttpException
     */
    public function setBot($bot = null): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
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
     * @throws ValidationException
     */
    public function addEvent(array $data): AppointmentEventResource
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
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

        if ($validator->fails())
            throw new ValidationException($validator);


        $tmp = (object)$data;
        $tmp->is_group = (bool)($tmp->is_group ?? false);
        $tmp->bot_id = $this->bot->id;
        $tmp->images = json_decode($tmp->images ?? '[]');

        $event = AppointmentEvent::query()->create((array)$tmp);

        return new AppointmentEventResource($event);

    }

    /**
     * @throws HttpException
     */
    public function duplicateEvent($appointmentEventId): AppointmentEventResource
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $event = AppointmentEvent::query()->find($appointmentEventId);

        if (is_null($event))
            throw new HttpException(404, "Событие не найдено!");

        $newEvent = $event->replicate();
        $newEvent->save();

        $services = AppointmentService::query()
            ->where("appointment_event_id", $event->id)
            ->get();

        foreach ($services as $service) {
            $newService = $service->replicate();
            $newService->appointment_event_id = $newEvent->id;
            $newService->save();
        }

        $times = AppointmentSchedule::query()
            ->where("appointment_event_id", $event->id)
            ->get();

        foreach ($times as $time) {
            $newTime = $time->replicate();
            $newTime->appointment_event_id = $newEvent->id;
            $newTime->save();
        }

        return new AppointmentEventResource($newEvent);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function updateEvent(array $data): AppointmentEventResource
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
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

        if ($validator->fails())
            throw new ValidationException($validator);

        //todo: notification user if change

        $tmp = (object)$data;
        $tmp->is_group = (bool)($tmp->is_group ?? false);
        $tmp->bot_id = $this->bot->id;
        $tmp->images = json_decode($tmp->images ?? '[]');

        $event = AppointmentEvent::query()->find($tmp->id);
        $event->update((array)$tmp);

        return new AppointmentEventResource($event);

    }

    /**
     * @throws HttpException
     */
    public function removeReview($reviewId, $force = false): AppointmentReviewResource
    {
        $review = !$force ?
            AppointmentReview::query()->where("id", $reviewId)
                ->first() :
            AppointmentReview::query()->withTrashed()->where("id", $reviewId)
                ->first();

        if (is_null($review))
            throw new HttpException(404, "Отзыв не найден");

        $tmp = $review;

        if ($force) {
            $review->forceDelete();
            return new AppointmentReviewResource($tmp);
        }

        $review->delete();

        return new AppointmentReviewResource($tmp);
    }


    /**
     * @throws HttpException
     */
    public function removeSchedule($scheduleId, $force = false): AppointmentScheduleResource
    {
        $schedule = !$force ?
            AppointmentSchedule::query()->where("id", $scheduleId)
                ->first() :
            AppointmentEvent::query()->withTrashed()->where("id", $scheduleId)
                ->first();

        if (is_null($schedule))
            throw new HttpException(404, "Событие не найдено");

        $tmp = $schedule;

        if ($force) {
            $schedule->forceDelete();
            return new AppointmentScheduleResource($tmp);
        }

        $schedule->delete();

        return new AppointmentScheduleResource($tmp);
    }

    /**
     * @throws HttpException
     */
    public function removeEvent($appointmentEventId, $force = false): AppointmentEventResource
    {
        $event = !$force ?
            AppointmentEvent::query()->where("id", $appointmentEventId)
                ->first() :
            AppointmentEvent::query()->withTrashed()->where("id", $appointmentEventId)
                ->first();

        if (is_null($event))
            throw new HttpException(404, "Событие не найдено");
        $tmp = $event;

        if ($force) {

            $services = AppointmentService::query()
                ->where("appointment_event_id", $event->id)
                ->get();

            foreach ($services as $service)
                $service->forceDelete();

            $times = AppointmentSchedule::query()
                ->where("appointment_event_id", $event->id)
                ->get();

            foreach ($times as $time)
                $time->forceDelete();

            $appointments = Appointment::query()
                ->where("appointment_event_id", $event->id)
                ->get();

            foreach ($appointments as $appointment)
                $appointment->forceDelete();

            $reviews = AppointmentReview::query()
                ->where("appointment_event_id", $event->id)
                ->get();

            foreach ($reviews as $review)
                $review->forceDelete();

            $event->forceDelete();
            return new AppointmentEventResource($tmp);
        }

        $event->delete();

        return new AppointmentEventResource($tmp);
    }

    /**
     * @throws HttpException
     */
    public function restoreEvent($appointmentEventId): AppointmentEventResource
    {
        $event = AppointmentEvent::query()->withTrashed()->where("id", $appointmentEventId)
            ->first();

        if (is_null($event))
            throw new HttpException(404, "Событие не найдено");


        $event->deleted_at = null;
        $event->save();

        return new AppointmentEventResource($event);
    }


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function storeSchedule(array $data): AppointmentScheduleCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'schedule' => "required|array",
            'schedule.*.start_time' => "required",
            'schedule.*.day' => "required",
            'schedule.*.week' => "required",
            'schedule.*.month' => "required",
            'schedule.*.year' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $schedule = (object)$data["schedule"];
        $tmpSchedule = [];

        foreach ($schedule as $item) {
            $item = (object)$item;

            unset($item->appointment);

            $item->appointment_event_id = $data["appointment_event_id"];


            $tmp = AppointmentSchedule::query()->find($item->id ?? null);

            if (!is_null($tmp)) {
                $tmp->update((array)$item);
                $tmpSchedule[] = $tmp;
            } else
                $tmpSchedule[] = AppointmentSchedule::query()->create((array)$item);

        }


        return new AppointmentScheduleCollection($tmpSchedule);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function addService(array $data): AppointmentServiceResource
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'title' => "required",
            'description' => "required",
            'category' => "required",
            'images' => "required",
            'price' => "required",
            //'services.*.discount_price'=> "required",
            //'services.*.need_prepayment'=> "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $tmp = (object)$data;
        $tmp->need_prepayment = (bool)($tmp->need_prepayment ?? false);

        $service = AppointmentService::query()->create((array)$tmp);


        return new AppointmentServiceResource($service);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function updateService(array $data): AppointmentServiceResource
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'title' => "required",
            'description' => "required",
            'category' => "required",
            'images' => "required",
            'price' => "required",
            //'services.*.discount_price'=> "required",
            //'services.*.need_prepayment'=> "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $tmp = (object)$data;
        $tmp->need_prepayment = (bool)($tmp->need_prepayment ?? false);

        $service = AppointmentService::query()->find($tmp->id);
        $service->update((array)$tmp);


        return new AppointmentServiceResource($service);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function addServices(array $data): AppointmentServiceCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'services' => "required|array",
            'services.*.title' => "required",
            'services.*.description' => "required",
            'services.*.category' => "required",
            'services.*.images' => "required",
            'services.*.price' => "required",
            //'services.*.discount_price'=> "required",
            //'services.*.need_prepayment'=> "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $services = $data["services"];

        $tmpServices = [];

        foreach ($services as $service) {
            $service = (object)$service;
            $service->need_prepayment = (bool)($service->need_prepayment ?? false);

            $tmpServices[] = AppointmentService::query()->create((array)$service);
        }

        return new AppointmentServiceCollection($tmpServices);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function addTimes(array $data): AppointmentScheduleCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'times' => "required|array",
            'times.*.start_time' => "required",
            'times.*.end_time' => "required",
            'times.*.day' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $times = $data["times"];

        $tmpTimes = [];

        foreach ($times as $time) {
            $tmpTimes[] = AppointmentSchedule::query()->create($time);
        }

        return new AppointmentScheduleCollection($tmpTimes);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function storeAppointment(array $data): AppointmentResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'appointment_schedule_id' => "required",
            //'bot_id',
            //'appointment_event_id',
            //'bot_user_id',
            //'appointment_schedule_id',
            //'status',
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $event = AppointmentEvent::query()
            ->find($data["appointment_event_id"]);

        if (is_null($event))
            throw new HttpException(404, "Событие не найдено!");

        $time = AppointmentSchedule::query()
            ->find($data["appointment_schedule_id"]);

        if (is_null($time))
            throw new HttpException(404, "Время для записи не найдено!");

        $hasAppointment = !is_null(Appointment::query()
            ->where("appointment_event_id", $data["appointment_event_id"])
            ->where("appointment_schedule_id", $data["appointment_schedule_id"])
            ->where("bot_id", $this->bot->id)
            ->first());

        if ($hasAppointment && is_null($data["id"]))
            throw new HttpException(403, "На данное время уже есть запись");

        $appointment = (object)$data;

        $appointment->bot_id = $this->bot->id;
        $appointment->bot_user_id = $this->botUser->id;
        $appointment->status = $appointment->status ?? 0;


        $tmpAppointment = Appointment::query()->find($appointment->id);
        if (is_null($tmpAppointment))
            $tmpAppointment = Appointment::query()->create((array)$appointment);

        $tmpAppointment->update((array)$appointment);

        return new AppointmentResource($tmpAppointment);

    }

    /**
     * @throws HttpException
     */
    public function changeAppointmentStatus(int $appointmentId, AppointmentStatusEnum $status): AppointmentResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(400, "Условия функции не выполнены!");


        $appointment = Appointment::query()
            ->where("id", $appointmentId)
            ->first();

        if (is_null($appointment))
            throw new HttpException(403, "Запись не найдена");
        //todo: add notification for bot user
        $appointment->status = $status->value;
        $appointment->save();

        return new AppointmentResource($appointment);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function storeReview(array $data): AppointmentReviewResource
    {
        if (is_null($this->botUser) || is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'appointment_event_id' => "required",
            'appointment_schedule_id' => "required",
            //'bot_user_id',
            'rating' => "required",
            'text' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $appointment = Appointment::query()
            ->where("appointment_event_id", $data["appointment_event_id"])
            ->where("appointment_schedule_id", $data["appointment_schedule_id"])
            ->where("bot_user_id", $this->botUser->id)
            ->first();

        if (is_null($appointment)) {
            $appointment = Appointment::query()->create([
                'bot_id' => $this->bot->id,
                'appointment_event_id' => $data["appointment_event_id"],
                'bot_user_id' => $this->botUser->id,
                'appointment_schedule_id' => $data["appointment_schedule_id"],
                'status' => AppointmentStatusEnum::Complete->value,
            ]);
            // throw new HttpException(403, "Вы не посещали данное мероприятие");
        }

        if ($appointment->status != AppointmentStatusEnum::Complete->value)
            throw new HttpException(403, "Вы еще не посетили данное мероприятие");

        /*   $hasReview = !is_null(AppointmentReview::query()
               ->where("appointment_event_id", $data["appointment_event_id"])
               ->where("appointment_schedule_id", $data["appointment_schedule_id"])
               ->where("bot_user_id", $this->botUser->id)
               ->first());

           if ($hasReview)
               throw new HttpException(400, "Отзыв уже добавлен");*/

        $review = (object)$data;

        $review->bot_user_id = $this->botUser->id;
        unset($review->bot_id);

        $tmpReview = AppointmentReview::query()->updateOrCreate((array)$review);

        return new AppointmentReviewResource($tmpReview);

    }

    /**
     * @throws HttpException
     */
    public function removeService($serviceId, $force = false): AppointmentServiceResource
    {
        $service = !$force ?
            AppointmentService::query()->where("id", $serviceId)
                ->first() :
            AppointmentService::query()->withTrashed()->where("id", $serviceId)
                ->first();

        if (is_null($service))
            throw new HttpException(404, "Сервис не найден!");
        $tmp = $service;

        if ($force) {
            $service->forceDelete();
            return new AppointmentServiceResource($tmp);
        }

        $service->delete();

        return new AppointmentServiceResource($tmp);
    }

    /**
     * @throws HttpException
     */
    public function removeTime($scheduleId, $force = false): AppointmentScheduleResource
    {
        $time = !$force ?
            AppointmentSchedule::query()->where("id", $scheduleId)
                ->first() :
            AppointmentSchedule::query()->withTrashed()->where("id", $scheduleId)
                ->first();

        if (is_null($time))
            throw new HttpException(404, "Время в расписании не найдено!");
        $tmp = $time;

        if ($force) {
            $time->forceDelete();
            return new AppointmentScheduleResource($tmp);
        }

        $time->delete();

        return new AppointmentScheduleResource($tmp);
    }

    /**
     * @throws HttpException
     */
    public function removeAppointment($appointmentId, $force = false): AppointmentResource
    {
        $appointment = !$force ?
            Appointment::query()->where("id", $appointmentId)
                ->first() :
            Appointment::query()->withTrashed()->where("id", $appointmentId)
                ->first();

        if (is_null($appointment))
            throw new HttpException(404, "Запись не найдена!");

        $tmp = $appointment;

        if ($force) {
            $appointment->forceDelete();
            return new AppointmentResource($tmp);
        }

        $appointment->delete();

        return new AppointmentResource($tmp);
    }

    public function serviceCategoryList($appointmentEventId)
    {

        $categories = AppointmentService::query()
            ->withTrashed()
            ->where("appointment_event_id", $appointmentEventId)
            ->get()
            ->unique("category")
            ->pluck("category");

        return $categories->toArray();
    }

    public function serviceList($appointmentEventId, $search = null, $size = null, $order = null, $direction = null): AppointmentServiceCollection
    {

        $size = $size ?? config('app.results_per_page');

        $services = AppointmentService::query()
            ->withTrashed()
            ->where("appointment_event_id", $appointmentEventId);

        if (!is_null($search))
            $services = $services->where(function ($q) use ($search) {
                $q->where("title", 'like', "%$search%")
                    ->orWhere("category", 'like', "%$search%")
                    ->orWhere("description", 'like', "%$search%");
            });


        $services = $services
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new AppointmentServiceCollection($services);

    }

    public function scheduleList($appointmentEventId, $date = null, $size = null, $order = null, $direction = null): AppointmentScheduleCollection
    {
        $size = $size ?? config('app.results_per_page');

        $times = AppointmentSchedule::query()
            ->withTrashed()
            ->with(["appointment"])
            ->where("appointment_event_id", $appointmentEventId);

        $date = !is_null($date) ? Carbon::parse($date) : Carbon::now("+3");
        $week = $date->weekOfYear;
        $month = $date->month;
        $year = $date->year;

        $times = $times->where("week", $week)
            ->where("month", $month - 1)
            ->where("year", $year);


        $times = $times->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->get();

        return new AppointmentScheduleCollection($times);
    }

    public function reviewList($appointmentEventId, $size = null, $order = null, $direction = null): AppointmentReviewCollection
    {
        $size = $size ?? config('app.results_per_page');

        $times = AppointmentReview::query()
            //->withTrashed()
            ->where("appointment_event_id", $appointmentEventId)
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new AppointmentReviewCollection($times);
    }

    /**
     * @throws HttpException
     */
    public function eventList($search = null, $isGroup = false, $size = null, $order = null, $direction = null): AppointmentEventCollection
    {

        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $events = AppointmentEvent::query()
           // ->withTrashed()
            ->where("bot_id", $this->bot->id)
            ->where("is_group", $isGroup);

        if (!is_null($search))
            $events = $events->where(function ($q) use ($search) {
                $q->where("title", 'like', "%$search%")
                    ->orWhere("subtitle", 'like', "%$search%")
                    ->orWhere("description", 'like', "%$search%");
            });


        $events = $events
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new AppointmentEventCollection($events);
    }

    public function appointmentList($eventId = null, $status = null): AppointmentCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $appointments = Appointment::query()
            ->withTrashed()
            ->with(["schedule"])
            ->where("bot_id", $this->bot->id);

        if (!is_null($eventId))
            $appointments = $appointments->where("appointment_event_id", $eventId);

        if (!is_null($status))
            $appointments = $appointments->where("status", $status);


        $appointments = $appointments
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new AppointmentCollection($appointments);
    }
}
