<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class DatabaseSeeder extends Seeder {

    public function run(): void {

        $status = 'active';

        User::create([
            'name' => 'Base User Administrator',
            'type' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('123456789'),
        ]);

        for ($i=0; $i < 10; $i++) {

            $name = 'Test User ' . $i;
            $email = 'test' . $i . '@example.com';
            $status = ($i === 3 || $i === 6 || $i === 9) ? 'inactive' : 'active';

            User::create([
                'name' => $name,
                'status' => $status,
                'email' => $email,
                'password' => Hash::make('123456789'),
            ]);

        }

    }

}