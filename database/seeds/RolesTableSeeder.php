<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        Role::insert([
            ['name' => Role::ADMIN_ROLE_NAME],
            ['name' => Role::OPERATOR_ROLE_NAME]
        ]);
    }
}
