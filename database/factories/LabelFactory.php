<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Label>
 */
class LabelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Bug',
                'Feature',
                'Urgent',
                'Backend',
                'Frontend',
                'API',
                'Database',
                'Testing',
                'Documentation',
                'Security',
            ]),

            'colour' => fake()->hexColor(),
        ];
    }
}