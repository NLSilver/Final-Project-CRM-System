<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role'     => 'admin', //
        ]);

        User::create([
            'name'     => 'Manager',
            'email'    => 'manager@gmail.com',
            'password' => Hash::make('12345678'),
            'role'     => 'manager', //
        ]);

        User::create([
            'name'     => 'Sales',
            'email'    => 'sales@gmail.com',
            'password' => Hash::make('12345678'),
            'role'     => 'sales_staff', //
        ]);

        $staffNames = [
            'Alice Thompson', 'Bob Richards', 'Charlie Davis', 'Diana Prince', 
            'Edward Norton', 'Fiona Gallagher', 'George Miller', 'Hannah Abbott',
            'Ian Wright', 'Jenna Ortega'
        ];

        foreach ($staffNames as $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'sales_staff',
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
