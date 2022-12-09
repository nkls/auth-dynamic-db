<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShardCoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('shard_coordinators')->insert([
                [
                    'uuid' => '68a93f35-5904-470a-9d59-212d159d2d6d',
                    'name' => 'Demo',
                    'subdomain' => 'demo',
                    'dbname' => 'auth_demo',
                ],
            ]);
        } catch (\Exception) {
        }
    }
}


