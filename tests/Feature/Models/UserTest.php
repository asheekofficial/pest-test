<?php

use App\Models\Course;
use App\Models\CourseTransaction;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has courses relation', function () {

    $user = User::factory()->create();

    $courses = Course::factory()->count(2)
        ->released()
        ->create();

    foreach ($courses as $course) {
        CourseTransaction::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    expect($user->courses)
        ->toHaveCount(2)
        ->each()
        ->toBeInstanceOf(Course::class);
});

it('has videos relation', function () {

    $user = User::factory()->create();

    $videos = Video::factory()->count(2)
    ->create();

    $user->videos()->attach($videos);

    expect($user->videos)
        ->toHaveCount(2)
        ->each()
        ->toBeInstanceOf(Video::class);
});