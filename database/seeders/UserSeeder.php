<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
            'fullname' => 'Admin',
            'username' => 'admin',
            'telp_number' => '081234567890',
            'profile' => 'profile/1775103083.jpg',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => '12345678',]);

        User::firstorCreate(
            ['email' => 'catluminate@gmail.com'],
            [
                'fullname' => 'Catluminate',
                'username' => 'catluminate',
                'telp_number' => '081234567891',
                'profile' => 'profile/1775103083.jpg',
                'role' => 'users',
                'email_verified_at' => now(),
                'password' => '12345',
            ]);
        
        User::firstorCreate(
            ['email' => 'notary@gmail.com'],
            [
                'fullname' => 'Notary',
                'username' => 'notary',
                'telp_number' => '081234567892',
                'profile' => 'profile/1775103083.jpg',
                'role' => 'notary',
                'email_verified_at' => now(),
                'password' => '12345',
            ]);
        
            User::firstorCreate(
                ['email' => 'agent@gmail.com'],
                [
                    'fullname' => 'Agent',
                    'username' => 'agent',
                    'telp_number' => '081234567893',
                    'profile' => 'profile/1775103083.jpg',
                    'role' => 'agent',
                    'email_verified_at' => now(),
                    'password' => '12345',
                ]);
    }
}
