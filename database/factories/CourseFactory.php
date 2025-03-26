<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->slug(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function released(?Carbon $date = null)
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'published_at' => $date ?? Carbon::now(),
            ];
        });
    }
}
