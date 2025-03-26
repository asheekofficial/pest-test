<?php

use App\Models\Course;
use App\Models\CourseTransaction;
use App\Models\User;
use App\Models\Video;

use function Pest\Laravel\get;

it('gives back successful response for home page', function () {
    get(route('home'))
        ->assertOk();

});

it('gives successful response for course details page', function () {

    $course = Course::factory()->released()->create();

    get(route('course-details', $course))
        ->assertOk();
});

it('gives successful response for dashboard page', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('dashboard.index'))
        ->assertOk();
});

it('gives successful response for video details page', function () {
    $course = Course::factory()->create() ;

    $video = Video::factory()->create([
        'course_id' => $course->id
    ]);

    $user = loginAsUser();

    CourseTransaction::factory()->create([
        'course_id' => $course->id,
        'user_id' => $user->id,
    ]);
    
    get(route('video-details',['video' => $video]))
        ->assertOk();
});