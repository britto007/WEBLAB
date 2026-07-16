<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fms.com'],
            [
                'name' => 'FMS Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '01700000000',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@fms.com'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '01800000000',
                'email_verified_at' => now(),
            ]
        );
    }
}
