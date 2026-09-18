<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),

            'name' => fake()->unique()->words(
                fake()->numberBetween(2, 4),
                true
            ),

            'description' => fake()->paragraph(),

            'status' => fake()->randomElement([
                'active',
                'completed',
                'archived',
            ]),
        ];
    }
}