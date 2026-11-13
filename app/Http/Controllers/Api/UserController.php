<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for users: list, show, create, update and (soft) delete users.

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * GET /api/users
     *
     * Users that are not deleted. ?include_deleted=1 lists deleted users too.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = User::query()
            ->unless($request->boolean('include_deleted'), fn ($query) => $query->where('deleted', false))
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return UserResource::collection($users);
    }

    /**
     * POST /api/users
     */
    public function store(UserRequest $request): JsonResponse
    {
        $user = User::create($request->modelData())->refresh();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * GET /api/users/{user}
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * PUT/PATCH /api/users/{user}
     */
    public function update(UserRequest $request, User $user): UserResource
    {
        $user->update($request->modelData());

        return new UserResource($user);
    }

    /**
     * DELETE /api/users/{user}
     *
     * Soft delete: sets the table's `deleted` flag instead of removing the row.
     * A hard delete would fail anyway, because carts and orders reference the user
     * (ON DELETE SET DEFAULT on a NOT NULL column with no default), and it would lose order history.
     */
    public function destroy(User $user): Response
    {
        $user->update(['deleted' => true]);

        return response()->noContent();
    }
}
