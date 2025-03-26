<h1>Total Courses:{{count($courses)}}</h1>

@foreach ($courses as $course)
    <h1>{{$course->title}}</h1>
    <p>{{$course->description}}</p>

    <li>
        @foreach($course->videos as $video)
            <ol><a href="{{route('video-details', $video)}}"> {{$video->title}} </a></ol>
        @endforeach
    </li>
@endforeach