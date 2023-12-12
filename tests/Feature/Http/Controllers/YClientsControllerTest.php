<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\YClient;
use App\Models\YClients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Bots\Web\YClientsController
 */
class YClientsControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $yClients = YClients::factory()->count(3)->create();

        $response = $this->get(route('y-client.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\YClientsController::class,
            'store',
            \App\Http\Requests\YClientsStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $need_debug = $this->faker->boolean;
        $throttle = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('y-client.store'), [
            'bot_id' => $bot->id,
            'need_debug' => $need_debug,
            'throttle' => $throttle,
        ]);

        $yClients = YClient::query()
            ->where('bot_id', $bot->id)
            ->where('need_debug', $need_debug)
            ->where('throttle', $throttle)
            ->get();
        $this->assertCount(1, $yClients);
        $yClient = $yClients->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $yClient = YClients::factory()->create();

        $response = $this->get(route('y-client.show', $yClient));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\YClientsController::class,
            'update',
            \App\Http\Requests\YClientsUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $yClient = YClients::factory()->create();
        $bot = Bot::factory()->create();
        $need_debug = $this->faker->boolean;
        $throttle = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('y-client.update', $yClient), [
            'bot_id' => $bot->id,
            'need_debug' => $need_debug,
            'throttle' => $throttle,
        ]);

        $yClient->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $yClient->bot_id);
        $this->assertEquals($need_debug, $yClient->need_debug);
        $this->assertEquals($throttle, $yClient->throttle);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $yClient = YClients::factory()->create();
        $yClient = YClient::factory()->create();

        $response = $this->delete(route('y-client.destroy', $yClient));

        $response->assertNoContent();

        $this->assertModelMissing($yClient);
    }
}
