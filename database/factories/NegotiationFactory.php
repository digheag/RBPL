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
            // 'seller_id' => User::inRandomOrder()->value('id'),
            // 'agent_id' => Agent::inRandomOrder()->value('id'),
            // 'buyer_id' => User::inRandomOrder()->value('id'),
            'offer_price' => fake()->numberBetween(80000000, 150000000),
            'description' => fake()->sentence(10),
            // 'is_agen_approve' => fake()->boolean(70),
            // 'is_seller_approve' => fake()->boolean(60),
            'seller_id' => 2,
            'agent_id' => 4,
            'buyer_id' => 2,
            'is_agen_approve' => null,
            'is_seller_approve' => null,
        ];
    }
        public function waitingSeller():static{
        return $this->state(fn () => [
            'property_id' => Property::inRandomOrder()->value('id'),

            'seller_id' => 2,
            'agent_id' => 4,
            'buyer_id' => 2,

            'is_agen_approve' => 1,
            'is_seller_approve' => null,
        ]);
        }

        public function approvedByAgent(): static
        {
        return $this->state(fn () => [
            'property_id' => Property::inRandomOrder()->value('id'),

            'seller_id' => 2,
            'agent_id' => 4,
            'buyer_id' => 2,

            'is_agen_approve' => 1,
            'is_seller_approve' => 0,
        ]);
        }

        public function approvedBySeller(): static
        {
        return $this->state(fn () => [
            'property_id' => Property::inRandomOrder()->value('id'),

            'seller_id' => 2,
            'agent_id' => 4,
            'buyer_id' => 2,

            'is_agen_approve' => 1,
            'is_seller_approve' => 1,
        ]);
        }

        public function reject(): static
    {
        return $this->state(fn () => [
            'property_id' => Property::inRandomOrder()->value('id'),
            'seller_id' => 2,
            'agent_id' => 4,
            'buyer_id' => 2,
            'is_agen_approve' => 0,
            'is_seller_approve' => 0,
        ]);
    }
}
