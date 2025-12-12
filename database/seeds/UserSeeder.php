<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@educonnect.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@educonnect.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
        ]);

        User::create([
            'name' => 'Student User',
            'email' => 'student@educonnect.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Demo User',
            'email' => 'demo@educonnect.com',
            'password' => Hash::make('demo123'),
            'role' => 'student',
        ]);
    }
}
