<?php

use App\Models\Course;
use App\Models\CourseTransaction;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

use function Pest\Laravel\get;

// uses(RefreshDatabase::class);

it('cannot be accessed by guest', function () {
    get(route('dashboard.index'))
        ->assertRedirectToRoute('login');
});

it('only shows purchased courses', function () {
    $user = loginAsUser();

    $courses = Course::factory()->count(2)
        ->released()
        ->state(new Sequence(
            ['title' => 'Course 1', 'description' => 'Description 1'],
            ['title' => 'Course 2', 'description' => 'Description 2'],
        ))
        ->create();

    foreach ($courses as $course) {
        CourseTransaction::factory()->create([
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);
    }

    get(route('dashboard.index'))
        ->assertSeeText('Course 1')
        ->assertSeeText('Description 1')
        ->assertSeeText('Course 2')
        ->assertSeeText('Description 2')
        ->assertOk();
});

it('does not shows other courses', function () {
    $user = User::factory()->create();

    $purchasedCourse = Course::factory()->released()->create();

    $unPurchasedCourse = Course::factory()->released()->create();

    CourseTransaction::factory()->create([
        'course_id' => $purchasedCourse->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    get(route('dashboard.index'))
        ->assertSeeText($purchasedCourse->title)
        ->assertSeeText($purchasedCourse->description)
        ->assertDontSeeText($unPurchasedCourse->title)
        ->assertDontSeeText($unPurchasedCourse->description)
        ->assertOk();
});

it('shows purchased courses in purchased date order', function () {

    $user = User::factory()->create();

    $firstCourse = Course::factory()->released()->create([
        'title' => 'Course 1', 'description' => 'Description 1',
    ]);

    $secondCourse = Course::factory()->released()->create(
        [
            'title' => 'Course 2', 'description' => 'Description 2',
        ]
    );

    CourseTransaction::factory()->create([
        'course_id' => $firstCourse->id,
        'user_id' => $user->id,
        'created_at' => Carbon::yesterday(),
    ]);

    CourseTransaction::factory()->create([
        'course_id' => $secondCourse->id,
        'user_id' => $user->id,
        'created_at' => Carbon::now(),
    ]);

    $this->actingAs($user);

    get(route('dashboard.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Course 2',
            'Description 2',
            'Course 1',
            'Description 1',
        ]);
});

it('includes the number of purchased courses', function () {

    $user = User::factory()->create();

    $firstCourse = Course::factory()->released()->create([
        'title' => 'Course 1', 'description' => 'Description 1',
    ]);

    $secondCourse = Course::factory()->released()->create(
        [
            'title' => 'Course 2', 'description' => 'Description 2',
        ]
    );

    CourseTransaction::factory()->create([
        'course_id' => $firstCourse->id,
        'user_id' => $user->id,
    ]);

    CourseTransaction::factory()->create([
        'course_id' => $secondCourse->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    get(route('dashboard.index'))
        ->assertOk()
        ->assertSeeText('Total Courses:2');
});

it('shows links to videos', function () {

    $user = User::factory()->create();

    $course = Course::factory()->released()
        ->has(Video::factory()->count(5))
        ->create([
            'title' => 'Course 1', 'description' => 'Description 1',
        ]);

    CourseTransaction::factory()->create([
        'course_id' => $course->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = get(route('dashboard.index'));

    $course->videos->each(function ($video) use ($response) {
        $response->assertSee(route('video-details', ['video' => $video]));
    });

    $response->assertOk();

});
