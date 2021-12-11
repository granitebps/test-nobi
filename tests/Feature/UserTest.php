<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\post;

it('can store user', function () {
    $user = User::factory()->make()->toArray();

    expect(User::count())->toEqual(0);

    post(route('user.store'), [])
        ->assertStatus(422);

    $user['password'] = 'password';
    post(route('user.store'), $user)
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll('data.token', 'data.id')
                ->etc()
        );

    expect(PersonalAccessToken::count())->toEqual(1);
    expect(User::count())->toEqual(1);
});
