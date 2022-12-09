<?php

namespace App\Models\Route;

use App\Helpers\DataBase;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(
            DataBase::addConnection(
                config('database.default') . '_' . config('database.route'),
                config('database.route')
            )
        );
    }

    public function getConnectionTable(): string
    {
        return $this->getConnectionName() . '.' . $this->getTable();
    }
}
