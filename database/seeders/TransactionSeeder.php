<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Negotiation;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::factory()->count(1)
        ->create();

        // Negotiation::factory()->count(10)
        // ->create();

        Negotiation::factory()
        ->waitingSeller()
        ->create();

        Negotiation::factory()
        ->approvedByAgent()
        ->create();

        Negotiation::factory()
        ->approvedBySeller()
        ->create();

        Negotiation::factory()
        ->reject()
        ->create();
    }
}
