<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\Property;
use App\Models\Appoinment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\property>
 */
class propertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appoinment_id' => Appoinment::inRandomOrder()->value('id'),
            'name' => fake()->words(3, true),
            'address' => fake()->address(),
            'price' => fake()->numberBetween(100000, 1000000),
            'sold_date' => fake()->boolean(30) ? fake()->dateTime() : null,
            'area_in_hectare' => fake()->randomFloat(2, 0.1, 10),
            'description' => fake()->paragraph(),
        ];
    }
}
