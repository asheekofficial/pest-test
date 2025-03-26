<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows course details', function () {

    $course = Course::factory()->released()->create([
        'tagline' => 'test tag line',
        'image' => 'image.png',
        'learnings' => ['learning 1', 'learning 2'],
    ]);

    get(route('course-details', $course))
        ->assertSeeText([
            $course->tagline,
            $course->title,
            'learning 1',
            'learning 2',
        ])
        ->assertSee('image.png')
        ->assertOk();
});

it('shows course video count', function () {
    $course = Course::factory()->released()
        ->has(Video::factory()->count(5))
        ->create();

    get(route('course-details', $course))
        ->assertSeeText($course->videosCount().' Videos')
        ->assertOk();
});
