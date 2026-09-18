<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function listForUser(
        User $user,
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->projectRepository->getForUser(
            $user,
            $perPage
        );
    }

    public function create(
        User $user,
        array $data
    ): Project {
        return DB::transaction(function () use ($user, $data) {

            $project = $this->projectRepository->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'active',
                'owner_id' => $user->id,
            ]);

            /*
             * Owner becomes manager.
             */
            $this->projectRepository->attachUser(
                $project,
                $user,
                'manager'
            );

            /*
             * Add selected project members.
             */
            $this->projectRepository->attachUsers(
                $project,
                $data['members'] ?? [],
                'member'
            );

            return $project->fresh([
                'owner',
                'users',
            ]);
        });
    }

    public function getForView(
        Project $project
    ): Project {
        return $this->projectRepository->getForView(
            $project
        );
    }

    public function update(
        Project $project,
        array $data
    ): Project {
        return DB::transaction(function () use (
            $project,
            $data
        ) {

            $project = $this->projectRepository->update(
                $project,
                [
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'status' => $data['status'],
                ]
            );

            /*
             * Sync project members.
             *
             * Owner is always preserved.
             */
            if (array_key_exists('members', $data)) {

                $this->projectRepository->syncUsers(
                    $project,
                    $data['members'] ?? [],
                    $project->owner
                );
            }

            return $project->fresh([
                'owner:id,name,email',
                'users:id,name,email',
            ]);
        });
    }

    public function delete(
        Project $project
    ): void {
        DB::transaction(function () use ($project) {
            $this->projectRepository->delete($project);
        });
    }

    public function addMember(
        Project $project,
        User $user,
        string $role = 'member'
    ): void {
        $this->projectRepository->attachUser(
            $project,
            $user,
            $role
        );
    }

    public function removeMember(
        Project $project,
        User $user
    ): void {
        DB::transaction(function () use (
            $project,
            $user
        ) {

            $this->projectRepository->removeUser(
                $project,
                $user
            );

            $this->projectRepository->unassignTasks(
                $project,
                $user
            );
        });
    }
}