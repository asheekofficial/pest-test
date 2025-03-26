<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VideoPlayer extends Component
{
    public Video $video;
    public Course $course;
    public function render()
    {
        $courseVideos = $this->course->videos ;

        $videoAlreadyWatched = Auth::user()->videos()
        ->where('video_id', $this->video->id)
        ->exists();

        // dd($videoAlreadyWatched) ;

        return view('livewire.video-player', compact('courseVideos', 'videoAlreadyWatched'));
    }

    public function markVideoAsWatched() :void
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        $user->videos()->attach($this->video);
    }

    public function markVideoAsNotWatched() :void
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        $user->videos()->detach($this->video);
    }
}
