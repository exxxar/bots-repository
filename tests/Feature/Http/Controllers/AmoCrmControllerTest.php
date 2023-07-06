<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\255;
use App\Models\AmoCrm;
use App\Models\Bot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AmoCrmController
 */
class AmoCrmControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $amoCrms = AmoCrm::factory()->count(3)->create();

        $response = $this->get(route('amo-crm.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AmoCrmController::class,
            'store',
            \App\Http\Requests\AmoCrmStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $client = 255::factory()->create();
        $client_secret = $this->faker->word;
        $auth_code = $this->faker->word;
        $redirect_uri = $this->faker->word;
        $subdomain = $this->faker->word;

        $response = $this->post(route('amo-crm.store'), [
            'bot_id' => $bot->id,
            'client_id' => $client->id,
            'client_secret' => $client_secret,
            'auth_code' => $auth_code,
            'redirect_uri' => $redirect_uri,
            'subdomain' => $subdomain,
        ]);

        $amoCrms = AmoCrm::query()
            ->where('bot_id', $bot->id)
            ->where('client_id', $client->id)
            ->where('client_secret', $client_secret)
            ->where('auth_code', $auth_code)
            ->where('redirect_uri', $redirect_uri)
            ->where('subdomain', $subdomain)
            ->get();
        $this->assertCount(1, $amoCrms);
        $amoCrm = $amoCrms->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $amoCrm = AmoCrm::factory()->create();

        $response = $this->get(route('amo-crm.show', $amoCrm));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AmoCrmController::class,
            'update',
            \App\Http\Requests\AmoCrmUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $amoCrm = AmoCrm::factory()->create();
        $bot = Bot::factory()->create();
        $client = 255::factory()->create();
        $client_secret = $this->faker->word;
        $auth_code = $this->faker->word;
        $redirect_uri = $this->faker->word;
        $subdomain = $this->faker->word;

        $response = $this->put(route('amo-crm.update', $amoCrm), [
            'bot_id' => $bot->id,
            'client_id' => $client->id,
            'client_secret' => $client_secret,
            'auth_code' => $auth_code,
            'redirect_uri' => $redirect_uri,
            'subdomain' => $subdomain,
        ]);

        $amoCrm->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $amoCrm->bot_id);
        $this->assertEquals($client->id, $amoCrm->client_id);
        $this->assertEquals($client_secret, $amoCrm->client_secret);
        $this->assertEquals($auth_code, $amoCrm->auth_code);
        $this->assertEquals($redirect_uri, $amoCrm->redirect_uri);
        $this->assertEquals($subdomain, $amoCrm->subdomain);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $amoCrm = AmoCrm::factory()->create();

        $response = $this->delete(route('amo-crm.destroy', $amoCrm));

        $response->assertNoContent();

        $this->assertModelMissing($amoCrm);
    }
}
