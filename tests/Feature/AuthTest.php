<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\post;

it('can login', function (User $user) {
    expect(User::count())->toEqual(1);

    post(route('auth.login'), [
        'username' => 'a'
    ])
        ->assertStatus(422);

    post(route('auth.login'), [
        'username' => $user->username,
        'password' => 'randompass'
    ])
        ->assertStatus(400);

    post(route('auth.login'), [
        'username' => $user->username,
        'password' => 'password'
    ])
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll('data.token', 'data.id')
                ->where('data.id', $user->id)
                ->etc()
        );

    expect(PersonalAccessToken::count())->toEqual(1);
})->with('user');
