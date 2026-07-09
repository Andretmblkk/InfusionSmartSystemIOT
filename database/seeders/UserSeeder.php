<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['employee_id' => '2405001'],
            [
                'name' => 'Suster Amira',
                'email' => 'amira@rsudyowari.local',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
        );
    }
}
