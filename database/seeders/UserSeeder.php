<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Naliko',
            'email' => 'admin@naliko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Naliko',
            'email' => 'kasir@naliko.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);
    }
}