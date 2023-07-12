<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ReviewController
 */
class ReviewControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $reviews = Review::factory()->count(3)->create();

        $response = $this->get(route('review.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReviewController::class,
            'store',
            \App\Http\Requests\ReviewStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $rating = $this->faker->numberBetween(-10000, 10000);
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->post(route('review.store'), [
            'rating' => $rating,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'bot_id' => $bot->id,
        ]);

        $reviews = Review::query()
            ->where('rating', $rating)
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $reviews);
        $review = $reviews->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $review = Review::factory()->create();

        $response = $this->get(route('review.show', $review));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReviewController::class,
            'update',
            \App\Http\Requests\ReviewUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $review = Review::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('review.update', $review), [
            'rating' => $rating,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'bot_id' => $bot->id,
        ]);

        $review->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($rating, $review->rating);
        $this->assertEquals($user->id, $review->user_id);
        $this->assertEquals($product->id, $review->product_id);
        $this->assertEquals($bot->id, $review->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('review.destroy', $review));

        $response->assertNoContent();

        $this->assertModelMissing($review);
    }
}
