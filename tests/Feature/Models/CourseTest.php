<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('return only released courses', function () {
    $releasedCourse = Course::factory()->released()->create();

    Course::factory()->create();

    expect($releasedCourse)->toBeInstanceOf(Course::class);

    expect(Course::released()->get())
        ->toHaveCount(1)
        ->first()->id->toBe($releasedCourse->id);
});

it('has videos', function () {
    $course = Course::factory()->released()
        ->has(Video::factory()->count(5))
        ->create();

    expect($course->videos)
        ->toHaveCount(5)
        ->each()->toBeInstanceOf(Video::class);

    expect($course->videosCount())->toBe(5);
});
