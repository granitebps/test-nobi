<?php

use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\post;

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
