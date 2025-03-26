<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filePath = fake()->filePath;

        return [
            'course_id' => Course::factory(),
            'title' => fake()->text,
            'file' => $filePath,
            'duration' => 10,
            'description' => fake()->text,
        ];
    }
}
