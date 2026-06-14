<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ecommerce.com'],
            [
                'name'     => 'Admin',
                'apellido' => 'NexusTech',
                'email'    => 'admin@ecommerce.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
    }
}
