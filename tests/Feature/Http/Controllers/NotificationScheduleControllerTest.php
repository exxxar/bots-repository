<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Creator;
use App\Models\NotificationSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\NotificationScheduleController
 */
class NotificationScheduleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $notificationSchedules = NotificationSchedule::factory()->count(3)->create();

        $response = $this->get(route('notification-schedule.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\NotificationScheduleController::class,
            'store',
            \App\Http\Requests\NotificationScheduleStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $creator = Creator::factory()->create();

        $response = $this->post(route('notification-schedule.store'), [
            'bot_id' => $bot->id,
            'creator_id' => $creator->id,
        ]);

        $notificationSchedules = NotificationSchedule::query()
            ->where('bot_id', $bot->id)
            ->where('creator_id', $creator->id)
            ->get();
        $this->assertCount(1, $notificationSchedules);
        $notificationSchedule = $notificationSchedules->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $notificationSchedule = NotificationSchedule::factory()->create();

        $response = $this->get(route('notification-schedule.show', $notificationSchedule));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\NotificationScheduleController::class,
            'update',
            \App\Http\Requests\NotificationScheduleUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $notificationSchedule = NotificationSchedule::factory()->create();
        $bot = Bot::factory()->create();
        $creator = Creator::factory()->create();

        $response = $this->put(route('notification-schedule.update', $notificationSchedule), [
            'bot_id' => $bot->id,
            'creator_id' => $creator->id,
        ]);

        $notificationSchedule->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $notificationSchedule->bot_id);
        $this->assertEquals($creator->id, $notificationSchedule->creator_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $notificationSchedule = NotificationSchedule::factory()->create();

        $response = $this->delete(route('notification-schedule.destroy', $notificationSchedule));

        $response->assertNoContent();

        $this->assertModelMissing($notificationSchedule);
    }
}
