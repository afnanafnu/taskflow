<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * List all users.
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize(
            'viewAny',
            User::class
        );

        $users = $this->userService->list();

        return UserResource::collection($users);
    }

    /**
     * Create a user.
     */
    public function store(
        StoreUserRequest $request
    ): UserResource {
        $this->authorize(
            'create',
            User::class
        );

        $user = $this->userService->create(
            $request->validated()
        );

        return new UserResource($user);
    }

    /**
     * Show a user.
     */
    public function show(
        User $user
    ): UserResource {
        $this->authorize(
            'view',
            $user
        );

        $user = $this->userService->find(
            $user
        );

        return new UserResource($user);
    }

    /**
     * Update a user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): UserResource {
        $this->authorize(
            'update',
            $user
        );

        $user = $this->userService->update(
            $user,
            $request->validated()
        );

        return new UserResource($user);
    }

    /**
     * Delete a user.
     */
    public function destroy(
        User $user
    ): JsonResponse {
        $this->authorize(
            'delete',
            $user
        );

        $this->userService->delete(
            $user
        );

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
            'data' => null,
        ]);
    }
}