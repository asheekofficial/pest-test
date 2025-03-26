<span>{{$course->tagline}}</span>
<span>{{$course->title}}</span>

@foreach ($course->learnings ?? [] as $learning )
    <span>{{$learning}}</span>
@endforeach

<img src="{{$course->image}}" alt="">

<span>{{$course->videosCount()}} Videos</span>