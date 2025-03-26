<div>
    <h1>{{$video->title}}</h1>

    <p>{{$video->description}}</p>

    <span>{{$video->duration}} mins</span>

    <iframe src="https://player.vimeo.com/video/{{$video->vimeo_video_id}}" allowfullscreen></iframe>

    @if($videoAlreadyWatched)
        <button wire:click='markVideoAsNotWatched'>Mark as not completed</button>
    @else
        <button wire:click='markVideoAsWatched'>Mark as completed</button>
    @endif

    <div>
        <h5>All Videos</h5>

        <ol>
            @foreach ($courseVideos as $courseVideo)
                <li>
                    <a href="{{route('video-details', $courseVideo)}}"
                    class="{{ $video->id == $courseVideo->id ? 'active' : 'not-active' }}" >
                        {{$courseVideo->title}}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
</div>
