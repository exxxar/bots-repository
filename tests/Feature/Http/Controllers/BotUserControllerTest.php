<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotUserController
 */
class BotUserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botUsers = BotUser::factory()->count(3)->create();

        $response = $this->get(route('bot-user.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotUserController::class,
            'store',
            \App\Http\Requests\BotUserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $user = User::factory()->create();
        $is_admin = $this->faker->boolean;
        $is_work = $this->faker->boolean;
        $user_in_location = $this->faker->boolean;

        $response = $this->post(route('bot-user.store'), [
            'bot_id' => $bot->id,
            'user_id' => $user->id,
            'is_admin' => $is_admin,
            'is_work' => $is_work,
            'user_in_location' => $user_in_location,
        ]);

        $botUsers = BotUser::query()
            ->where('bot_id', $bot->id)
            ->where('user_id', $user->id)
            ->where('is_admin', $is_admin)
            ->where('is_work', $is_work)
            ->where('user_in_location', $user_in_location)
            ->get();
        $this->assertCount(1, $botUsers);
        $botUser = $botUsers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botUser = BotUser::factory()->create();

        $response = $this->get(route('bot-user.show', $botUser));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotUserController::class,
            'update',
            \App\Http\Requests\BotUserUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botUser = BotUser::factory()->create();
        $bot = Bot::factory()->create();
        $user = User::factory()->create();
        $is_admin = $this->faker->boolean;
        $is_work = $this->faker->boolean;
        $user_in_location = $this->faker->boolean;

        $response = $this->put(route('bot-user.update', $botUser), [
            'bot_id' => $bot->id,
            'user_id' => $user->id,
            'is_admin' => $is_admin,
            'is_work' => $is_work,
            'user_in_location' => $user_in_location,
        ]);

        $botUser->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botUser->bot_id);
        $this->assertEquals($user->id, $botUser->user_id);
        $this->assertEquals($is_admin, $botUser->is_admin);
        $this->assertEquals($is_work, $botUser->is_work);
        $this->assertEquals($user_in_location, $botUser->user_in_location);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botUser = BotUser::factory()->create();

        $response = $this->delete(route('bot-user.destroy', $botUser));

        $response->assertNoContent();

        $this->assertModelMissing($botUser);
    }
}
