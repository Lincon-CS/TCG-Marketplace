<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Account
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);

        // Standard User Account
        User::factory()->create([
            'name' => 'Standard User',
            'email' => 'user@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);
    }
}