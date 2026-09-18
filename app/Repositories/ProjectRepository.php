<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getForUser(
        User $user,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Project::query()
            ->with(['owner:id,name,email'])
            ->withCount(['tasks', 'users']);

        if (! $user->isAdmin()) {
            $query->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhereHas('users', function ($query) use ($user) {
                        $query->whereKey($user->id);
                    });
            });
        }

        return $query
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function getForView(Project $project): Project
    {
        return $project->load([
            'owner:id,name,email',
            'users:id,name,email',
            'tasks' => fn ($query) => $query->latest(),
            'tasks.assignee:id,name,email',
            'tasks.labels:id,name,colour',
            'tasks.comments.user:id,name',
        ]);
    }

    public function update(
        Project $project,
        array $data
    ): Project {
        $project->update($data);

        return $project->fresh([
            'owner:id,name,email',
            'users:id,name,email',
        ]);
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    public function attachUser(
        Project $project,
        User $user,
        string $role
    ): void {
        $project->users()->syncWithoutDetaching([
            $user->id => [
                'role' => $role,
            ],
        ]);
    }

    public function attachUsers(
        Project $project,
        array $userIds,
        string $role = 'member'
    ): void {
        $members = [];

        foreach ($userIds as $userId) {
            $members[$userId] = [
                'role' => $role,
            ];
        }

        if (! empty($members)) {
            $project->users()->syncWithoutDetaching($members);
        }
    }

    public function syncUsers(
        Project $project,
        array $userIds,
        User $owner
    ): void {
        /*
         * Owner must always remain attached
         * as project manager.
         */
        $members = [
            $owner->id => [
                'role' => 'manager',
            ],
        ];

        foreach ($userIds as $userId) {

            /*
             * Prevent owner from being added again.
             */
            if ((int) $userId === (int) $owner->id) {
                continue;
            }

            $members[$userId] = [
                'role' => 'member',
            ];
        }

        $project->users()->sync($members);
    }

    public function removeUser(
        Project $project,
        User $user
    ): void {
        $project->users()->detach($user->id);
    }

    public function unassignTasks(
        Project $project,
        User $user
    ): void {
        $project->tasks()
            ->where('assignee_id', $user->id)
            ->update([
                'assignee_id' => null,
            ]);
    }
}