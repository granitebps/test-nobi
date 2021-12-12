<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\postJson;

it('can login', function (User $user) {
    expect(User::count())->toEqual(1);

    postJson(route('auth.login'), [
        'username' => 'a'
    ])
        ->assertStatus(422);

    postJson(route('auth.login'), [
        'username' => $user->username,
        'password' => 'randompass'
    ])
        ->assertStatus(400);

    postJson(route('auth.login'), [
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
