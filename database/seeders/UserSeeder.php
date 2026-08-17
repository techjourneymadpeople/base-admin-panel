<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'role' => 'Owner',
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'role' => 'Admin',
            ],
            [
                'name' => 'Support Specialist',
                'email' => 'support@example.com',
                'role' => 'Support',
            ],
            [
                'name' => 'Content Editor',
                'email' => 'editor@example.com',
                'role' => 'Editor',
            ],
            [
                'name' => 'Standard User',
                'email' => 'user@example.com',
                'role' => 'User',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );

            // Assign role
            $user->syncRoles([$userData['role']]);
        }
    }
}
