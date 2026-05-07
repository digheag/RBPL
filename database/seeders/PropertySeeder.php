<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Spesification;
use App\Models\Facilities;
use App\Models\Property_image;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::factory()->count(10)
        ->has(Property_image::factory()->count(10), 'Property_image')
        ->has(Spesification::factory()->count(5), 'Spesification')
        ->has(Facilities::factory()->count(5), 'Facilities')
        ->create();
    }
}
