<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\CourseTransaction;
use App\Models\User;
use App\Models\Video;

use function Pest\Laravel\get;

it('cannot be accessed by guest',function () {
   $course = Course::factory()
   ->released()
   ->has(Video::factory()->count(1))->create();

   $video = $course->videos->first();

   get(route('video-details', $video))
   ->assertRedirect() ;
});

it('can only be accessed by course purchased user',function () {
    $user = loginAsUser();

    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(1))
    ->create();

    $video = $course->videos->first();

    CourseTransaction::factory()->create([
        'course_id' => $course->id,
        'user_id' => $user->id,
    ]);

    get(route('video-details', $video))
    ->assertOk();
});

it('contains a video player',function(){
    $user = loginAsUser();

    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(1))
    ->create();

    $video = $course->videos->first();

    CourseTransaction::factory()->create([
        'course_id' => $course->id,
        'user_id' => $user->id,
    ]);

    get(route('video-details', $video))
    ->assertOk()
    ->assertSeeLivewire(VideoPlayer::class);
});