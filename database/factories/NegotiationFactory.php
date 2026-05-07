<?php

namespace Database\Factories;
use app\Models\Property;
use app\Models\User;
use app\Models\Agent;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Negotiation>
 */
class NegotiationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::inRandomOrder()->value('id'),
            'seller_id' => User::inRandomOrder()->value('id'),
            'agent_id' => Agent::inRandomOrder()->value('id'),
            'buyer_id' => User::inRandomOrder()->value('id'),
            'offer_price' => fake()->numberBetween(80000000, 150000000),
            'description' => fake()->sentence(10),
            'is_agen_approve' => fake()->boolean(70),
            'is_seller_approve' => fake()->boolean(60),
        ];
    }
}
