<?php

use App\Models\User;
use App\Models\ViewModels\Nab;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can get list nab from latest', function () {
    $response = get(route('ib.listNAB'))
        ->assertStatus(200)
        ->json();
    $firstData = $response['data'][0];
    $secondData = $response['data'][1];
    expect($firstData['date'] > $secondData['date'])->toBeTrue();
})->with('fiveNab');

it('can update nab using update total balance api', function () {
    post(route('ib.updateTotalBalance'), [])
        ->assertStatus(422);

    post(route('ib.updateTotalBalance'), [
        'current_balance' => 100000
    ])
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.nab')
                ->etc()
        );
});

it('can top up user', function () {
    $user = User::factory()->create([
        'unit' => 0,
    ]);

    post(route('ib.topup'), [
        'user_id' => $user->id,
        'amount_rupiah' => 10000
    ])->assertStatus(200);
});

it('can withdraw user', function () {
    $user = User::factory()->create([
        'unit' => 100000,
    ]);

    post(route('ib.topup'), [
        'user_id' => $user->id,
        'amount_rupiah' => 10000
    ])->assertStatus(200);
});
