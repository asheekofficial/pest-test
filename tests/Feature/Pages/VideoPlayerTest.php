<?php

use App\Models\Course;
use App\Models\Video;
use Livewire\Livewire;

it('shows details for given video',function(){
    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(1))
    ->create();

    $video = $course->videos->first();

    $this->assertEquals($video->duration, 10) ;

    Livewire::test('video-player', ['video' => $video, 'course' => $course])
    ->assertOk()
    ->assertSeeText($video->title)
    ->assertSeeText($video->description)
    ->assertSeeText($video->duration." mins");
});

it('shows video player',function(){
    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(1)
    ->state(['vimeo_video_id' => 1]))
    ->create();

    $video = $course->videos->first();

    Livewire::test('video-player', ['video' => $video, 'course' => $course])
    ->assertOk()
    ->assertSeeHtml(sprintf('<iframe src="https://player.vimeo.com/video/%d" allowfullscreen></iframe>', $video->vimeo_video_id));
});

it('shows list of all videos',function(){
    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(3))
    ->create();

    $videos = $course->videos;

    $response = Livewire::test('video-player', ['video' => $videos->first(), 'course' => $course])
    ->assertOk()
    ->assertSeeText('All Videos') ;

    foreach ($videos as $video) {
        $expectedClass = $video->id === $videos->first()->id ;

        $response->assertSee('href="' . route('video-details', $video) . '"', false)
        ->assertSeeText($video->title);

        if ($expectedClass) {
            $response->assertSee('class="active"', false);
        } else {
            $response->assertSee('class="not-active"', false);
        }
    }
});

it('can mark video as watched',function(){
    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(1))
    ->create();

    $video = $course->videos->first();

    $user = loginAsUser();

    expect($user->videos)->toHaveCount(0);

    Livewire::test('video-player', ['video' => $video, 'course' => $course])
    ->call('markVideoAsWatched');

    $user->refresh() ;

    expect($user->videos)->toHaveCount(1);
});

it('can mark video as not completed',function(){
    $course = Course::factory()
    ->released()
    ->has(Video::factory()->count(1))
    ->create();

    $video = $course->videos->first();

    $user = loginAsUser();

    $user->videos()->attach($video);

    expect($user->videos)->toHaveCount(1);

    Livewire::test('video-player', ['video' => $video, 'course' => $course])
    ->call('markVideoAsNotWatched');

    $user->refresh() ;

    expect($user->videos)->toHaveCount(0);
});