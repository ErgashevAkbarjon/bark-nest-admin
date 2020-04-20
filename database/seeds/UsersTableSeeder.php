<?php

use App\Role;
use App\User;
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
        User::truncate();

        User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', '12345678')),
            'role_id' => Role::where('name', Role::ADMIN_ROLE_NAME)->first()->id
        ]);

        User::create([
            'name' => env('OPERATOR_NAME', 'Operator'),
            'email' => env('OPERATOR_EMAIL', 'operator@gmail.com'),
            'password' => Hash::make(env('OPERATOR_PASSWORD', '12345678')),
            'role_id' => Role::where('name', Role::OPERATOR_ROLE_NAME)->first()->id
        ]);
    }
}
