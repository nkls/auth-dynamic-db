<?php

namespace App\Console\Commands\Saml2\Traits;

use App\Helpers\DataBase;
use Illuminate\Console\Command;
use Slides\Saml2\Repositories\TenantRepository;

trait OrganisationDatabaseTrait
{
    public function __construct()
    {
        $this->signature .= ' {dbname}';

        Command::__construct();
    }

    public function handle()
    {
        DataBase::setDefault($this->argument('dbname'));
        $this->tenants = app(TenantRepository::class);
        parent::handle();
    }
}
