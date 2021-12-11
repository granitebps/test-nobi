<?php

use App\Models\ViewModels\Nab;

dataset('nab', [
    fn () => Nab::factory()->create()
]);

dataset('fiveNab', [
    fn () => Nab::factory()->count(5)->create()
]);
