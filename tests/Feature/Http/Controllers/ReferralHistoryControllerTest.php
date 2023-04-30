<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\ReferralHistory;
use App\Models\UserRecipient;
use App\Models\UserSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ReferralHistoryController
 */
class ReferralHistoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $referralHistories = ReferralHistory::factory()->count(3)->create();

        $response = $this->get(route('referral-history.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReferralHistoryController::class,
            'store',
            \App\Http\Requests\ReferralHistoryStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user_sender = UserSender::factory()->create();
        $user_recipient = UserRecipient::factory()->create();
        $bot = Bot::factory()->create();
        $activated = $this->faker->boolean;

        $response = $this->post(route('referral-history.store'), [
            'user_sender_id' => $user_sender->id,
            'user_recipient_id' => $user_recipient->id,
            'bot_id' => $bot->id,
            'activated' => $activated,
        ]);

        $referralHistories = ReferralHistory::query()
            ->where('user_sender_id', $user_sender->id)
            ->where('user_recipient_id', $user_recipient->id)
            ->where('bot_id', $bot->id)
            ->where('activated', $activated)
            ->get();
        $this->assertCount(1, $referralHistories);
        $referralHistory = $referralHistories->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $referralHistory = ReferralHistory::factory()->create();

        $response = $this->get(route('referral-history.show', $referralHistory));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReferralHistoryController::class,
            'update',
            \App\Http\Requests\ReferralHistoryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $referralHistory = ReferralHistory::factory()->create();
        $user_sender = UserSender::factory()->create();
        $user_recipient = UserRecipient::factory()->create();
        $bot = Bot::factory()->create();
        $activated = $this->faker->boolean;

        $response = $this->put(route('referral-history.update', $referralHistory), [
            'user_sender_id' => $user_sender->id,
            'user_recipient_id' => $user_recipient->id,
            'bot_id' => $bot->id,
            'activated' => $activated,
        ]);

        $referralHistory->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user_sender->id, $referralHistory->user_sender_id);
        $this->assertEquals($user_recipient->id, $referralHistory->user_recipient_id);
        $this->assertEquals($bot->id, $referralHistory->bot_id);
        $this->assertEquals($activated, $referralHistory->activated);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $referralHistory = ReferralHistory::factory()->create();

        $response = $this->delete(route('referral-history.destroy', $referralHistory));

        $response->assertNoContent();

        $this->assertModelMissing($referralHistory);
    }
}
