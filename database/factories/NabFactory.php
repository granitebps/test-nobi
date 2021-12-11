<?php

namespace Database\Factories;

use App\Models\ViewModels\Nab;
use Illuminate\Database\Eloquent\Factories\Factory;

class NabFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Nab::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTime(),
            'nab' => $this->faker->randomFloat(4, 1, 10)
        ];
    }
}
