<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Sample users for roles
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'role' => 'admin', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'guru@example.com'],
            ['name' => 'Guru User', 'role' => 'guru', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'wali@example.com'],
            ['name' => 'Wali Murid User', 'role' => 'wali_murid', 'password' => bcrypt('password')]
        );

        // Ensure requested user gets admin role
        User::where('email', 'ainunnaja222@gmail.com')->update(['role' => 'admin']);
    }
}
