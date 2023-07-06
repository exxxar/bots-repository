<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ActionStatusController
 */
class ActionStatusControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $actionStatuses = ActionStatus::factory()->count(3)->create();

        $response = $this->get(route('action-status.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ActionStatusController::class,
            'store',
            \App\Http\Requests\ActionStatusStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $script = $this->faker->word;
        $max_attempts = $this->faker->numberBetween(-10000, 10000);
        $current_attempts = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('action-status.store'), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'script' => $script,
            'max_attempts' => $max_attempts,
            'current_attempts' => $current_attempts,
        ]);

        $actionStatuses = ActionStatus::query()
            ->where('user_id', $user->id)
            ->where('bot_id', $bot->id)
            ->where('script', $script)
            ->where('max_attempts', $max_attempts)
            ->where('current_attempts', $current_attempts)
            ->get();
        $this->assertCount(1, $actionStatuses);
        $actionStatus = $actionStatuses->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $actionStatus = ActionStatus::factory()->create();

        $response = $this->get(route('action-status.show', $actionStatus));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ActionStatusController::class,
            'update',
            \App\Http\Requests\ActionStatusUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $actionStatus = ActionStatus::factory()->create();
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $script = $this->faker->word;
        $max_attempts = $this->faker->numberBetween(-10000, 10000);
        $current_attempts = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('action-status.update', $actionStatus), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'script' => $script,
            'max_attempts' => $max_attempts,
            'current_attempts' => $current_attempts,
        ]);

        $actionStatus->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $actionStatus->user_id);
        $this->assertEquals($bot->id, $actionStatus->bot_id);
        $this->assertEquals($script, $actionStatus->script);
        $this->assertEquals($max_attempts, $actionStatus->max_attempts);
        $this->assertEquals($current_attempts, $actionStatus->current_attempts);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $actionStatus = ActionStatus::factory()->create();

        $response = $this->delete(route('action-status.destroy', $actionStatus));

        $response->assertNoContent();

        $this->assertModelMissing($actionStatus);
    }
}
