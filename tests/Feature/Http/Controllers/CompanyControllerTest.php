<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\CompanyController
 */
class CompanyControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $companies = Company::factory()->count(3)->create();

        $response = $this->get(route('company.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\CompanyController::class,
            'store',
            \App\Http\Requests\CompanyStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        $response = $this->post(route('company.store'), [
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $companies = Company::query()
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $companies);
        $company = $companies->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $company = Company::factory()->create();

        $response = $this->get(route('company.show', $company));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\CompanyController::class,
            'update',
            \App\Http\Requests\CompanyUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $company = Company::factory()->create();
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('company.update', $company), [
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $company->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($slug, $company->slug);
        $this->assertEquals($is_active, $company->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $company = Company::factory()->create();

        $response = $this->delete(route('company.destroy', $company));

        $response->assertNoContent();

        $this->assertModelMissing($company);
    }
}
