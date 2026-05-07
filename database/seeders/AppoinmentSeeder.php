<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appoinment;
use App\Models\Appoinment_schedule;
use App\Models\User;
use App\Models\Agent;
use App\Models\District;

class AppoinmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agent = Agent::first();
        $sellers = User::all();
        $districts = District::all();

        Appoinment::factory()
            ->count(5)
            ->make()
            ->each(function ($a) use ($agent, $sellers, $districts) {
                $appointment = Appoinment::create([
                    'agent_id' => $agent->id,
                    'seller_id' => $sellers->random()->id,
                    'district_id' => $districts->random()->id,
                    'property_name' => $a->property_name,
                    'property_address' => $a->property_address,
                    'actual_time_schedule' => $a->actual_time_schedule,
                    'is_approved_by_agen' => $a->is_approved_by_agen,
                ]);

                if ($a->actual_time_schedule) {
                    Appoinment_schedule::factory()
                        ->create([
                            "appointment_id" => $appointment->id,
                            "schedule" => $a->actual_time_schedule,
                            "is_agen_approve_schedule" => true,
                            "is_seller_approve_schedule" => true,
                        ]);
                } else {
                    Appoinment_schedule::factory()
                        ->create([
                            "appointment_id" => $appointment->id,
                            "is_agen_approve_schedule" => false,
                            "is_seller_approve_schedule" => true,
                        ]);
                }
            });
    }
}

