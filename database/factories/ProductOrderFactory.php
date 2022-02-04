<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'total_price' => $this->faker->numberBetween(500, 10000),
            'status' => 'pending',
            'payment_reference' => $this->faker->uuid()
        ];
    }
}
