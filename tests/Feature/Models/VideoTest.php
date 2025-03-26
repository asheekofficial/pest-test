<?php

use App\Models\Course;
use App\Models\Video;

it('has course',function(){
    $video = Video::factory()
    ->has(Course::factory())
    ->create();

    expect($video->course)
    ->toBeInstanceOf(Course::class);
});