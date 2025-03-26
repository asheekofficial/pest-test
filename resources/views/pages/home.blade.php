<span>
    @if(!Auth::check())
        <a href="{{route('login')}}">Login</a>
    @else
        <a href="{{route('logout')}}">Logout</a>
    @endif

</span>

@foreach ($courses as $course)
    <h1>{{$course->title}}</h1>
    <p>{{$course->description}}</p>
@endforeach