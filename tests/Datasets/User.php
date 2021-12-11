<?php

use App\Models\User;

dataset('user', [
    fn () => User::factory()->create()
]);
