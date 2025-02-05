<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mercato.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street',
        ]);

        // Create employees
        $employees = [
            [
                'name' => 'John Doe',
                'email' => 'john@mercato.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'phone' => '+1234567891',
                'address' => '456 Employee Ave',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@mercato.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'phone' => '+1234567892',
                'address' => '789 Staff Road',
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@mercato.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'phone' => '+1234567893',
                'address' => '321 Cashier Lane',
            ]
        ];

        foreach ($employees as $employee) {
            User::create($employee);
        }
    }
}
