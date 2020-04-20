<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
            'password' => env('ADMIN_PASSWORD', '12345678')
        ]);

        User::create([
            'name' => env('OPERATOR_NAME', 'Operator'),
            'email' => env('OPERATOR_EMAIL', 'operator@gmail.com'),
            'password' => env('OPERATOR_PASSWORD', '12345678')
        ]);
    }
}
