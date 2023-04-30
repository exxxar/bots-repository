<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\RoleController
 */
class RoleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $roles = Role::factory()->count(3)->create();

        $response = $this->get(route('role.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\RoleController::class,
            'store',
            \App\Http\Requests\RoleStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $name = $this->faker->name;
        $slug = $this->faker->slug;

        $response = $this->post(route('role.store'), [
            'name' => $name,
            'slug' => $slug,
        ]);

        $roles = Role::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->get();
        $this->assertCount(1, $roles);
        $role = $roles->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $role = Role::factory()->create();

        $response = $this->get(route('role.show', $role));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\RoleController::class,
            'update',
            \App\Http\Requests\RoleUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $role = Role::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;

        $response = $this->put(route('role.update', $role), [
            'name' => $name,
            'slug' => $slug,
        ]);

        $role->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($name, $role->name);
        $this->assertEquals($slug, $role->slug);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $role = Role::factory()->create();

        $response = $this->delete(route('role.destroy', $role));

        $response->assertNoContent();

        $this->assertModelMissing($role);
    }
}
