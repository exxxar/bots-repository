<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::all();

        return new EventCollection($events);
    }

    public function store(EventStoreRequest $request): Response
    {
        $event = Event::create($request->validated());

        return new EventResource($event);
    }

    public function show(Request $request, Event $event): Response
    {
        return new EventResource($event);
    }

    public function update(EventUpdateRequest $request, Event $event): Response
    {
        $event->update($request->validated());

        return new EventResource($event);
    }

    public function destroy(Request $request, Event $event): Response
    {
        $event->delete();

        return response()->noContent();
    }
}
