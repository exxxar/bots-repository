<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\ImageMenu;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ImageMenuController
 */
class ImageMenuControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $imageMenus = ImageMenu::factory()->count(3)->create();

        $response = $this->get(route('image-menu.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ImageMenuController::class,
            'store',
            \App\Http\Requests\ImageMenuStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $product_count = $this->faker->numberBetween(-10000, 10000);
        $location = Location::factory()->create();

        $response = $this->post(route('image-menu.store'), [
            'bot_id' => $bot->id,
            'product_count' => $product_count,
            'location_id' => $location->id,
        ]);

        $imageMenus = ImageMenu::query()
            ->where('bot_id', $bot->id)
            ->where('product_count', $product_count)
            ->where('location_id', $location->id)
            ->get();
        $this->assertCount(1, $imageMenus);
        $imageMenu = $imageMenus->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $imageMenu = ImageMenu::factory()->create();

        $response = $this->get(route('image-menu.show', $imageMenu));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ImageMenuController::class,
            'update',
            \App\Http\Requests\ImageMenuUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $imageMenu = ImageMenu::factory()->create();
        $bot = Bot::factory()->create();
        $product_count = $this->faker->numberBetween(-10000, 10000);
        $location = Location::factory()->create();

        $response = $this->put(route('image-menu.update', $imageMenu), [
            'bot_id' => $bot->id,
            'product_count' => $product_count,
            'location_id' => $location->id,
        ]);

        $imageMenu->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $imageMenu->bot_id);
        $this->assertEquals($product_count, $imageMenu->product_count);
        $this->assertEquals($location->id, $imageMenu->location_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $imageMenu = ImageMenu::factory()->create();

        $response = $this->delete(route('image-menu.destroy', $imageMenu));

        $response->assertNoContent();

        $this->assertModelMissing($imageMenu);
    }
}
