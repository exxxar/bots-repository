<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Document;
use App\Models\Documents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DocumentsController
 */
class DocumentsControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $documents = Documents::factory()->count(3)->create();

        $response = $this->get(route('document.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DocumentsController::class,
            'store',
            \App\Http\Requests\DocumentsStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $type = $this->faker->numberBetween(-10000, 10000);
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();

        $response = $this->post(route('document.store'), [
            'type' => $type,
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
        ]);

        $documents = Document::query()
            ->where('type', $type)
            ->where('bot_id', $bot->id)
            ->where('bot_user_id', $bot_user->id)
            ->get();
        $this->assertCount(1, $documents);
        $document = $documents->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $document = Documents::factory()->create();

        $response = $this->get(route('document.show', $document));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DocumentsController::class,
            'update',
            \App\Http\Requests\DocumentsUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $document = Documents::factory()->create();
        $type = $this->faker->numberBetween(-10000, 10000);
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();

        $response = $this->put(route('document.update', $document), [
            'type' => $type,
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
        ]);

        $document->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($type, $document->type);
        $this->assertEquals($bot->id, $document->bot_id);
        $this->assertEquals($bot_user->id, $document->bot_user_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $document = Documents::factory()->create();
        $document = Document::factory()->create();

        $response = $this->delete(route('document.destroy', $document));

        $response->assertNoContent();

        $this->assertModelMissing($document);
    }
}
