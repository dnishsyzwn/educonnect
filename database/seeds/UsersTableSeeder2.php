<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing test users (keeps other users if any)
        DB::table('users')->whereIn('id', [1, 2])->delete();

        // Or delete all users (slower but thorough)
        // DB::table('users')->delete();
        // DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        // Create test user
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => Hash::make('password123'), // Default password
            'icno' => '990101-01-1234', // Example IC number
            'telno' => '+60123456789',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create additional test users if needed
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'icno' => '880202-02-5678',
            'telno' => '+60198765432',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('Test User Credentials:');
        $this->command->info('Email: test@example.com / Password: password123');
        $this->command->info('Admin Email: admin@example.com / Password: admin123');
    }
}
