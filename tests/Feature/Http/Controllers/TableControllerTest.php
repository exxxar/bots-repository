<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotUser;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TableController
 */
final class TableControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $tables = Table::factory()->count(3)->create();

        $response = $this->get(route('tables.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TableController::class,
            'store',
            \App\Http\Requests\TableStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $bot_user = BotUser::factory()->create();

        $response = $this->post(route('tables.store'), [
            'bot_user_id' => $bot_user->id,
        ]);

        $tables = Table::query()
            ->where('bot_user_id', $bot_user->id)
            ->get();
        $this->assertCount(1, $tables);
        $table = $tables->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $table = Table::factory()->create();

        $response = $this->get(route('tables.show', $table));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TableController::class,
            'update',
            \App\Http\Requests\TableUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $table = Table::factory()->create();
        $bot_user = BotUser::factory()->create();

        $response = $this->put(route('tables.update', $table), [
            'bot_user_id' => $bot_user->id,
        ]);

        $table->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot_user->id, $table->bot_user_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $table = Table::factory()->create();

        $response = $this->delete(route('tables.destroy', $table));

        $response->assertNoContent();

        $this->assertModelMissing($table);
    }
}
