<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index(Request $request): Response
    {
        $roles = Role::all();

        return new RoleCollection($roles);
    }

    public function store(RoleStoreRequest $request): Response
    {
        $role = Role::create($request->validated());

        return new RoleResource($role);
    }

    public function show(Request $request, Role $role): Response
    {
        return new RoleResource($role);
    }

    public function update(RoleUpdateRequest $request, Role $role): Response
    {
        $role->update($request->validated());

        return new RoleResource($role);
    }

    public function destroy(Request $request, Role $role): Response
    {
        $role->delete();

        return response()->noContent();
    }
}
