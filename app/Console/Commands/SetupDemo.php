<?php

namespace App\Console\Commands;

use App\Helpers\DataBase;
use Database\Seeders\Demo\ShardCoordinatorSeeder;
use Database\Seeders\Demo\TenantSeeder;
use Database\Seeders\Demo\UserSeeder;
use Exception;
use Illuminate\Console\Command;

class SetupDemo extends Command
{

    protected const DBNAME = 'auth_oxfordrisk';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup demo data.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DataBase::create(config('database.route'));
            $this->call('migrate', ['--path' => '/database/migrations/route']);
            $this->call('db:seed', [
                'class' => ShardCoordinatorSeeder::class
            ]);

            DataBase::create(static::DBNAME);
            $this->call('organisation:migrate', ['dbname' => static::DBNAME]);
            $this->call('db:seed', [
                '--database' => static::DBNAME,
                'class' => TenantSeeder::class
            ]);
            $this->call('db:seed', [
                '--database' => static::DBNAME,
                'class' => UserSeeder::class
            ]);

            return static::SUCCESS;
        } catch (Exception $e) {
            $this->components->error($e->getMessage());
        }

        return static::FAILURE;
    }
}
