<?php

use App\Models\User;

dataset('user', [
    fn () => User::factory()->create()
]);

dataset('fiveUsers', [
    fn () => User::factory()->count(5)->create()
]);
