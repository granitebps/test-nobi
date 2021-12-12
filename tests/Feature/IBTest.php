<?php

use App\Models\User;
use App\Models\ViewModels\Nab;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can get list nab from latest', function () {
    $response = getJson(route('ib.listNAB'))
        ->assertStatus(200)
        ->json();
    $firstData = $response['data'][0];
    $secondData = $response['data'][1];
    expect($firstData['date'] > $secondData['date'])->toBeTrue();
})->with('fiveNab');

it('can update nab using update total balance api', function () {
    postJson(route('ib.updateTotalBalance'), [])
        ->assertStatus(422);

    postJson(route('ib.updateTotalBalance'), [
        'current_balance' => 100000
    ])
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.nab')
                ->etc()
        );
});

it('can top up user', function (User $user, Nab $nab) {
    $token = $user->createToken(config('app.name'))->plainTextToken;
    $otherUser = User::factory()->create();

    postJson(route('ib.topup'), [
        'user_id' => $user->id,
        'amount_rupiah' => 10000
    ])->assertStatus(401);

    postJson(route('ib.topup'), [
        'user_id' => $otherUser->id,
        'amount_rupiah' => 10000
    ], [
        'Authorization' => 'Bearer ' . $token
    ])->assertStatus(403);

    $response = postJson(route('ib.topup'), [
        'user_id' => $user->id,
        'amount_rupiah' => 10000
    ], [
        'Authorization' => 'Bearer ' . $token
    ])->assertStatus(200)
        ->json();

    $user->refresh();

    $unit = round(10000 / $nab->nab, 4, PHP_ROUND_HALF_DOWN);

    expect($response['data'])->toMatchArray([
        'nilai_unit_hasil_topup' => $unit,
        'nilai_unit_total' => $user->unit,
        'saldo_rupiah_total' => $user->balance
    ]);
})->with('user', 'nab');

it('can withdraw user', function (Nab $nab) {
    $user = User::factory()->create([
        'unit' => 100000,
    ]);
    $otherUser = User::factory()->create();

    $token = $user->createToken(config('app.name'))->plainTextToken;

    postJson(route('ib.withdraw'), [
        'user_id' => $user->id,
        'amount_rupiah' => 10000
    ])->assertStatus(401);

    postJson(route('ib.withdraw'), [
        'user_id' => $otherUser->id,
        'amount_rupiah' => 10000
    ], [
        'Authorization' => 'Bearer ' . $token
    ])->assertStatus(403);

    $response = postJson(route('ib.withdraw'), [
        'user_id' => $user->id,
        'amount_rupiah' => 10000
    ], [
        'Authorization' => 'Bearer ' . $token
    ])->assertStatus(200)
        ->json();

    $user->refresh();

    $unit = round(10000 / $nab->nab, 4, PHP_ROUND_HALF_DOWN);

    expect($response['data'])->toMatchArray([
        'nilai_unit_setelah_withdraw' => $unit,
        'nilai_unit_total' => $user->unit,
        'saldo_rupiah_total' => $user->balance
    ]);
})->with('nab');

it('can get list of member', function () {
    $all = getJson(route('ib.member'))
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll('data.users', 'data.nab')
                ->has('data.users', 5)
                ->etc()
        )->json();

    expect($all['data']['users'][1])->toBeGreaterThan($all['data']['users'][0]);

    getJson(route('ib.member', [
        'limit' => 2
    ]))
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll('data.users', 'data.nab')
                ->has('data.users', 2)
                ->etc()
        );
})->with('fiveUsers');
