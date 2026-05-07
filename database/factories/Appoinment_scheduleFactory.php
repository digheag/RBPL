<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appoinment_schedule>
 */
class Appoinment_scheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule' => fake()->dateTime(),
            'is_agen_approve_schedule' => fake()->boolean(),
            'is_seller_approve_schedule' => fake()->boolean(),
        ];
    }
}
