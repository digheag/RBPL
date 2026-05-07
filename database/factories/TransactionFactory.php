<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Property;
use App\Models\User;
use App\Models\Agent;
use App\Models\Negotiation;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['direct', 'negotiation']);
        return [
        'property_id'=> Property::inRandomOrder()->value('id'),
        'seller_id' => User::inRandomOrder()->value('id'),
        'agent_id'=> Agent::inRandomOrder()->value('id'),
        'buyer_id'=> User::inRandomOrder()->value('id') ,
        'transaction_type' => $type,
        'negotiation_id' => $type === 'negotiation'
            ? Negotiation::factory()
            : null,
        'deal_price' => $this->generatePrice($type),
        ];
    }

    private function generatePrice($type)
    {
        return $type === 'negotiation'
            ? fake()->numberBetween(90000000, 150000000)
            : fake()->numberBetween(100000000, 200000000);
    }
}
