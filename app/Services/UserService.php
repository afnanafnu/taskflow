<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getAll($perPage);
    }

    public function find(User $user): User
    {
        return $this->userRepository->find($user);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);
    }

    public function update(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update(
            $user,
            $updateData
        );
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}