<?php

namespace App\Console\Commands;

use App\Helpers\DataBase;
use App\Resources\Route\ShardCoordinatorResource;
use Exception;
use Illuminate\Console\Command;

class OrganisationMigrate extends Command
{
    protected const FIELD_DBNAME = 'dbname';
    protected const MIGRATION_PATHS = [
        '/database/migrations',
        '/vendor/24slides/laravel-saml2/database/migrations',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organisation:migrate {dbname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate an organisation database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $dbname = $this->argument(static::FIELD_DBNAME);

            foreach (static::MIGRATION_PATHS as $path) {
                $this->call('migrate', array_filter([
                    '--database' => DataBase::addConnection($dbname, $dbname),
                    '--path' => $path
                ]));
            }

            return static::SUCCESS;
        } catch (Exception $e) {
            $this->components->error($e->getMessage());
        }

        return static::FAILURE;
    }
}
