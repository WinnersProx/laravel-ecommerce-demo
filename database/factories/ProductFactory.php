<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word('5'),
            'description' => $this->faker->word('20'),
            'price' =>  $this->faker->numberBetween(500, 30000),
            'quantity' => $this->faker->numberBetween(1, 50),
            'is_active' => $this->faker->boolean(true)
        ];
    }
}
