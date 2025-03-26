<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows courses overview', function () {

    $firstCourse = Course::factory()->released()->create();

    $secondCourse = Course::factory()->released()->create();

    get(route('home'))
        ->assertSeeText($firstCourse->title)
        ->assertSeeText($secondCourse->title)
        ->assertSeeText($secondCourse->title)
        ->assertSeeText($secondCourse->description)
        ->assertStatus(200);
});

it('shows only published courses', function () {

    $releasedCourse = Course::factory()->released()->create();

    $unReleasedCourse = Course::factory()->create();

    get(route('home'))
        ->assertSeeText($releasedCourse->title)
        ->assertDontSeeText($unReleasedCourse->title)
        ->assertStatus(200);

});

it('shows courses in published order', function () {
    $firstCourse = Course::factory()->released(Carbon::yesterday())->create();

    $secondCourse = Course::factory()->released()->create();

    get(route('home'))
        ->assertSeeTextInOrder([
            $secondCourse->title,
            $firstCourse->title,
        ])
        ->assertStatus(200);

});

it('shows login if not logged in', function () {
    get(route('home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});

it('shows logout if user is logged in', function () {
    $user = loginAsUser();

    get(route('home'))
        ->assertOk()
        ->assertSeeText('Logout')
        ->assertSee('logout');
});
