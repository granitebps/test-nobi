<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\postJson;

it('can store user', function (User $user) {
    $newUser = User::factory()->make()->toArray();

    expect(User::count())->toEqual(1);

    postJson(route('user.store'), [])
        ->assertStatus(422);

    postJson(route('user.store'), $user->toArray())
        ->assertStatus(422);

    $newUser['password'] = 'password';
    postJson(route('user.store'), $newUser)
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll('data.token', 'data.id')
                ->etc()
        );

    expect(PersonalAccessToken::count())->toEqual(1);
    expect(User::count())->toEqual(2);
})->with('user');
