<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Agent;
use App\Models\Agent_regency;
use App\Models\Regency;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->agen(),
        ];
    }

    public function withRegency($count = 2){
        return $this->afterCreating(function(Agent $agent) use ($count){
        $regencies = Regency::inRandomOrder()->limit($count)->get();
        foreach($regencies as $regency){

            Agent_regency::create([
                'agent_id' => $agent->id,
                'regency_id' => $regency->id,
            ]);
        }
        });
    }
}

