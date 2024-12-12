<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Folder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FolderController
 */
final class FolderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $folders = Folder::factory()->count(3)->create();

        $response = $this->get(route('folders.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FolderController::class,
            'store',
            \App\Http\Requests\FolderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $type = $this->faker->randomElement(/** enum_attributes **/);
        $is_active = $this->faker->boolean();

        $response = $this->post(route('folders.store'), [
            'type' => $type,
            'is_active' => $is_active,
        ]);

        $folders = Folder::query()
            ->where('type', $type)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $folders);
        $folder = $folders->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $folder = Folder::factory()->create();

        $response = $this->get(route('folders.show', $folder));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FolderController::class,
            'update',
            \App\Http\Requests\FolderUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $folder = Folder::factory()->create();
        $type = $this->faker->randomElement(/** enum_attributes **/);
        $is_active = $this->faker->boolean();

        $response = $this->put(route('folders.update', $folder), [
            'type' => $type,
            'is_active' => $is_active,
        ]);

        $folder->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($type, $folder->type);
        $this->assertEquals($is_active, $folder->is_active);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $folder = Folder::factory()->create();

        $response = $this->delete(route('folders.destroy', $folder));

        $response->assertNoContent();

        $this->assertModelMissing($folder);
    }
}
