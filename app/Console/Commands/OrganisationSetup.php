<?php

namespace App\Console\Commands;

use App\Helpers\DataBase;
use App\Resources\Route\ShardCoordinatorResource;
use Exception;
use Illuminate\Console\Command;

class OrganisationSetup extends Command
{
    protected const FIELD_SUBDOMAIN = 'subdomain';
    protected const FIELD_DBNAME = 'dbname';
    protected const FIELD_NAME = 'name';
    protected const MIGRATION_PATH = '/database/migrations';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organisation:setup
            {--subdomain= : The subdomain}
            {--dbname= : The name of the database}
            {--name= : The name of the organisation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add organisation and created, migrate DB.';

    protected array $data;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->handleName()
                ->handleSubdomain()
                ->handleDatabase()
                ->saveShardCoordinator()
                ->createDB()
                ->migrate();

            return static::SUCCESS;
        } catch (Exception $e) {
            $this->components->error($e->getMessage());
        }

        return static::FAILURE;
    }

    protected function handleSubdomain(): static
    {
        $value = $this->option(static::FIELD_SUBDOMAIN);

        if (empty($value)) {
            $value = $this->ask('What is the subdomain?');
        }

        if (empty($value)) {
            $this->handleSubdomain();
        }

        return $this->set(static::FIELD_SUBDOMAIN, $value);
    }

    protected function handleDatabase(): static
    {
        $value = $this->option(static::FIELD_DBNAME);

        if (empty($value)) {
            $value = $this->ask('What is the name of the database?');
        }

        if (empty($value)) {
            $this->handleDatabase();
        }

        return $this->set(static::FIELD_DBNAME, $value);
    }

    protected function handleName(): static
    {
        $value = $this->option(static::FIELD_NAME);

        if (empty($value)) {
            $value = $this->ask('What is the name of the organisation?');
        }

        if (empty($value)) {
            $this->handleName();
        }

        return $this->set(static::FIELD_NAME, $value);
    }

    protected function saveShardCoordinator(): static
    {
        if (!app(ShardCoordinatorResource::class)->create($this->get())) {
            $this->components->info(
                sprintf('Can not save the organisation "%s".', $this->get(static::FIELD_NAME))
            );

            return $this;
        }

        $this->components->info(
            sprintf('The organisation "%s" has been setup.', $this->get(static::FIELD_NAME))
        );

        return $this;
    }

    protected function createDB(): static
    {
        if (!DataBase::create($this->get(static::FIELD_DBNAME))) {
            $this->components->info(
                sprintf('Can not create the database schema "%s"', $this->get(static::FIELD_DBNAME))
            );

            return $this;
        }

        $this->components->info(
            sprintf('The database schema "%s" has been created.', $this->get(static::FIELD_DBNAME))
        );

        return $this;
    }

    protected function migrate(): static
    {
        $this->call('organisation:migrate', ['dbname' => $this->get(static::FIELD_DBNAME)]);

        return $this;
    }

    protected function get(string $field = null): string|array
    {
        return $field ? $this->data[$field] : $this->data;
    }

    protected function set(string $field, string $value): static
    {
        $this->data[$field] = $value;

        return $this;
    }
}
