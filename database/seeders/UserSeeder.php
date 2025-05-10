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
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ]);

        User::factory()->create([
            'name' => 'hr',
            'email' => 'hr@example.com',
        ]);
    }
}
