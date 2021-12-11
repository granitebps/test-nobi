<?php

use App\Models\User;
use App\Models\ViewModels\Nab;
use App\Models\ViewModels\Transaction;
use App\StorableEvents\Transaction as StorableEventsTransaction;

test('user cannot withdraw if user unit 0', function () {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create([
        'unit' => 0
    ]);

    event(new StorableEventsTransaction(
        $user->id,
        100000,
        'withdraw',
        now()->timestamp
    ));

    $user->refresh();
    expect($user->unit)->toEqual(0);

    expect(Transaction::count())->toEqual(0);
});

test('user cannot withdraw if user less than unit withdraw', function () {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create([
        'unit' => 100
    ]);

    event(new StorableEventsTransaction(
        $user->id,
        100000,
        'withdraw',
        now()->timestamp
    ));

    $user->refresh();
    expect($user->unit)->toEqual(100);

    expect(Transaction::count())->toEqual(0);
});

test('user can withdraw when nab random and user unit random', function (Nab $nab) {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create([
        'unit' => 1000000
    ]);

    event(new StorableEventsTransaction(
        $user->id,
        10000,
        'withdraw',
        now()->timestamp
    ));

    $updatedUser = $user->fresh();
    $expectedUnit = round(10000 / $nab->nab, 4, PHP_ROUND_HALF_DOWN);
    expect($updatedUser->unit)->toEqual($user->unit - $expectedUnit);

    expect(Transaction::count())->toEqual(1);

    $transaction = Transaction::first();
    expect($transaction->toArray())->toMatchArray([
        'user_id' => $user->id,
        'type' => 'withdraw',
        'nab' => $nab->nab,
        'unit' => $expectedUnit,
        'total_unit' => $updatedUser->unit,
        'amount' => 10000,
    ]);
})->with('nab');

test('user can withdraw when nab 1 and user unit random', function () {
    expect(Transaction::count())->toEqual(0);

    $user = User::factory()->create([
        'unit' => 1000000
    ]);

    event(new StorableEventsTransaction(
        $user->id,
        10000,
        'withdraw',
        now()->timestamp
    ));

    $updatedUser = $user->fresh();
    $expectedUnit = round(10000, 4, PHP_ROUND_HALF_DOWN);
    expect($updatedUser->unit)->toEqual($user->unit - $expectedUnit);

    expect(Transaction::count())->toEqual(1);

    $transaction = Transaction::first();
    expect($transaction->toArray())->toMatchArray([
        'user_id' => $user->id,
        'type' => 'withdraw',
        'nab' => 1,
        'unit' => $expectedUnit,
        'total_unit' => $updatedUser->unit,
        'amount' => 10000,
    ]);
});
