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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@oda-pos.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'pin' => '123456',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@oda-pos.com',
            'role' => 'manager',
            'password' => Hash::make('password'),
            'pin' => '111111',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@oda-pos.com',
            'role' => 'cashier',
            'password' => Hash::make('password'),
            'pin' => '222222',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Waiter User',
            'email' => 'waiter@oda-pos.com',
            'role' => 'waiter',
            'password' => Hash::make('password'),
            'pin' => '333333',
            'is_active' => true,
        ]);
    }
}
