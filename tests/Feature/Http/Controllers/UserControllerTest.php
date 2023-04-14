<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('user.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'store',
            \App\Http\Requests\UserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $email = $this->faker->safeEmail;
        $age = $this->faker->numberBetween(-10000, 10000);
        $sex = $this->faker->numberBetween(-10000, 10000);
        $role = Role::factory()->create();

        $response = $this->post(route('user.store'), [
            'email' => $email,
            'age' => $age,
            'sex' => $sex,
            'role_id' => $role->id,
        ]);

        $users = User::query()
            ->where('email', $email)
            ->where('age', $age)
            ->where('sex', $sex)
            ->where('role_id', $role->id)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.show', $user));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'update',
            \App\Http\Requests\UserUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $user = User::factory()->create();
        $email = $this->faker->safeEmail;
        $age = $this->faker->numberBetween(-10000, 10000);
        $sex = $this->faker->numberBetween(-10000, 10000);
        $role = Role::factory()->create();

        $response = $this->put(route('user.update', $user), [
            'email' => $email,
            'age' => $age,
            'sex' => $sex,
            'role_id' => $role->id,
        ]);

        $user->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($email, $user->email);
        $this->assertEquals($age, $user->age);
        $this->assertEquals($sex, $user->sex);
        $this->assertEquals($role->id, $user->role_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }
}
