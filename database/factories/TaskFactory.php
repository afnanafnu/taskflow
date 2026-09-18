<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),

            'assignee_id' => User::factory(),

            'title' => fake()->sentence(
                fake()->numberBetween(3, 8)
            ),

            'description' => fake()->paragraph(),

            'status' => fake()->randomElement([
                'todo',
                'in_progress',
                'completed',
                'blocked',
            ]),

            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
            ]),

            'due_date' => fake()->optional(0.8)->dateTimeBetween(
                'now',
                '+60 days'
            ),
        ];
    }
}