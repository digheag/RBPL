<?php

namespace Database\Seeders;

use App\Models\notary;
use App\Models\Property;
use app\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            /* NotarySeeder::class, */
            LocationSeeder::class,
            AgentSeeder::class,
            AppoinmentSeeder::class,
            PropertySeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
