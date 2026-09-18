<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $admin = User::factory()
            ->admin()
            ->create([
                'name' => 'TaskFlow Admin',
                'email' => 'admin@taskflow.test',
                'password' => 'password',
            ]);

        $users = User::factory()
            ->count(10)
            ->create();

        /*
        |--------------------------------------------------------------------------
        | Labels
        |--------------------------------------------------------------------------
        */

        $labels = Label::factory()
            ->count(10)
            ->create();

        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */

        $projects = collect();

        for ($i = 1; $i <= 5; $i++) {
            $owner = $users->random();

            $project = Project::factory()->create([
                'owner_id' => $owner->id,
                'name' => "TaskFlow Project {$i}",
                'status' => fake()->randomElement([
                    'active',
                    'active',
                    'completed',
                ]),
            ]);

            $projects->push($project);

            /*
            |--------------------------------------------------------------------------
            | Project Members
            |--------------------------------------------------------------------------
            */

            $members = $users
                ->where('id', '!=', $owner->id)
                ->random(
                    fake()->numberBetween(3, 6)
                );

            foreach ($members as $member) {
                $project->users()->attach(
                    $member->id,
                    [
                        'role' => fake()->randomElement([
                            'member',
                            'member',
                            'manager',
                        ]),
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Tasks
            |--------------------------------------------------------------------------
            */

            $projectUsers = $project->users()
                ->get()
                ->push($owner)
                ->unique('id')
                ->values();

            $tasks = Task::factory()
                ->count(fake()->numberBetween(6, 10))
                ->create([
                    'project_id' => $project->id,
                ]);

            foreach ($tasks as $task) {
                /*
                | Assign task to an actual project member.
                */
                $assignee = $projectUsers->random();

                $task->update([
                    'assignee_id' => $assignee->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Attach Labels
                |--------------------------------------------------------------------------
                */

                $taskLabels = $labels->random(
                    fake()->numberBetween(1, 3)
                );

                $task->labels()->sync(
                    $taskLabels->pluck('id')
                );

                /*
                |--------------------------------------------------------------------------
                | Comments
                |--------------------------------------------------------------------------
                */

                $commentUsers = $projectUsers;

                Comment::factory()
                    ->count(fake()->numberBetween(1, 3))
                    ->create([
                        'task_id' => $task->id,
                        'user_id' => $commentUsers->random()->id,
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Output
        |--------------------------------------------------------------------------
        */

        $this->command->info('TaskFlow demo data created successfully.');

        $this->command->info(
            "Admin login: admin@taskflow.test / password"
        );

        $this->command->info(
            "Normal user password: password"
        );
    }
}
