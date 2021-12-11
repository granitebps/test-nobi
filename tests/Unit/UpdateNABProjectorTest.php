<?php

use App\Models\User;
use App\Models\ViewModels\Nab;
use App\StorableEvents\UpdateNAB;

test('nab updated after event triggered', function () {
    expect(Nab::count())->toEqual(0);

    event(new UpdateNAB(1000, now()->timestamp));

    expect(Nab::count())->toEqual(1);
    $nab = Nab::first();
    expect($nab->nab)->toEqual(1);

    User::factory()->create([
        'unit' => 100
    ]);
    User::factory()->create([
        'unit' => 150
    ]);

    event(new UpdateNAB(1000000, now()->timestamp));
    expect(Nab::count())->toEqual(2);
    $latestNab = Nab::orderBy('id', 'desc')->first();
    $cal = 1000000 / 250;
    $currentNab = round($cal, 4, PHP_ROUND_HALF_DOWN);
    expect($latestNab->nab)->toEqual($currentNab);
});
