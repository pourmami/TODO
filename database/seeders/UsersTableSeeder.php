<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('123');

        User::trunicate();

        User::create([
            'first_name' => "Mohammad",
            'last_name' => "Pourmami",
            'email' => "m.pourmami@gmail.com",
            'password' => $password,
            'is_admin' => 1
        ]);

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => $password,
                'is_admin' => 0
            ]);
        }
    }
}
