<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\notary;

class NotarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        notary::factory(10)->create();
    }
}
