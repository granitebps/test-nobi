<?php

use App\Models\User;
use App\Models\ViewModels\Nab;
use App\Models\ViewModels\Transaction;
use App\StorableEvents\Transaction as StorableEventsTransaction;

test('user can topup when nab 1 and user unit 0', function () {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create([
        'unit' => 0
    ]);

    event(new StorableEventsTransaction(
        $user->id,
        100000,
        'topup',
        now()->timestamp
    ));

    $user->refresh();
    expect($user->unit)->toEqual(100000);

    expect(Transaction::count())->toEqual(1);

    $transaction = Transaction::first();
    expect($transaction->toArray())->toMatchArray([
        'user_id' => $user->id,
        'type' => 'topup',
        'nab' => 1,
        'unit' => 100000,
        'total_unit' => $user->unit,
        'amount' => 100000,
    ]);
});

test('user can topup when nab random and user unit 0', function (Nab $nab) {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create([
        'unit' => 0
    ]);

    event(new StorableEventsTransaction(
        $user->id,
        100000,
        'topup',
        now()->timestamp
    ));

    $user->refresh();
    $expectedUnit = round(100000 / $nab->nab, 4, PHP_ROUND_HALF_DOWN);
    expect($user->unit)->toEqual($expectedUnit);

    expect(Transaction::count())->toEqual(1);

    $transaction = Transaction::first();
    expect($transaction->toArray())->toMatchArray([
        'user_id' => $user->id,
        'type' => 'topup',
        'nab' => $nab->nab,
        'unit' => $expectedUnit,
        'total_unit' => $user->unit,
        'amount' => 100000,
    ]);
})->with('nab');

test('user can topup when nab random and user unit random', function (Nab $nab) {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create();

    event(new StorableEventsTransaction(
        $user->id,
        100000,
        'topup',
        now()->timestamp
    ));

    $updatedUser = $user->fresh();
    $expectedUnit = round(100000 / $nab->nab, 4, PHP_ROUND_HALF_DOWN);
    expect($updatedUser->unit)->toEqual($expectedUnit + $user->unit);

    expect(Transaction::count())->toEqual(1);

    $transaction = Transaction::first();
    expect($transaction->toArray())->toMatchArray([
        'user_id' => $user->id,
        'type' => 'topup',
        'nab' => $nab->nab,
        'unit' => $expectedUnit,
        'total_unit' => $updatedUser->unit,
        'amount' => 100000,
    ]);
})->with('nab');

test('user can topup when nab 1 and user unit random', function () {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create();

    event(new StorableEventsTransaction(
        $user->id,
        100000,
        'topup',
        now()->timestamp
    ));

    $updatedUser = $user->fresh();
    $expectedUnit = round(100000, 4, PHP_ROUND_HALF_DOWN);
    expect($updatedUser->unit)->toEqual($expectedUnit + $user->unit);

    expect(Transaction::count())->toEqual(1);

    $transaction = Transaction::first();
    expect($transaction->toArray())->toMatchArray([
        'user_id' => $user->id,
        'type' => 'topup',
        'nab' => 1,
        'unit' => $expectedUnit,
        'total_unit' => $updatedUser->unit,
        'amount' => 100000,
    ]);
});
