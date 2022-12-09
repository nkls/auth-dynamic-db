<?php

namespace Database\Seeders\Demo;

use App\Models\Organisation\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('users')->insert([
                [
                    'uuid' => Str::uuid()->toString(),
                    'role' => User::ROLE_ADMIN,
                    'name' => 'Admin',
                    'email' => 'admin@demo.xyz',
                    'password' => bcrypt('admin'),
                ], [
                    'uuid' => Str::uuid()->toString(),
                    'role' => User::ROLE_USER,
                    'name' => 'Adviser',
                    'email' => 'adviser@demo.xyz',
                    'password' => bcrypt('adviser'),
                ]
            ]);
        } catch (\Exception) {}
    }
}


