<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Geo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\GeoController
 */
class GeoControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $geos = Geo::factory()->count(3)->create();

        $response = $this->get(route('geo.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\GeoController::class,
            'store',
            \App\Http\Requests\GeoStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->post(route('geo.store'), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
        ]);

        $geos = Geo::query()
            ->where('user_id', $user->id)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $geos);
        $geo = $geos->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $geo = Geo::factory()->create();

        $response = $this->get(route('geo.show', $geo));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\GeoController::class,
            'update',
            \App\Http\Requests\GeoUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $geo = Geo::factory()->create();
        $user = User::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('geo.update', $geo), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
        ]);

        $geo->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $geo->user_id);
        $this->assertEquals($bot->id, $geo->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $geo = Geo::factory()->create();

        $response = $this->delete(route('geo.destroy', $geo));

        $response->assertNoContent();

        $this->assertModelMissing($geo);
    }
}
