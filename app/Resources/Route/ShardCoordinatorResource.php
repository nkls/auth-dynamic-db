<?php

namespace App\Resources\Route;

use App\Models\Route\ShardCoordinator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ShardCoordinatorResource
{
    public const FIELD_DBNAME = 'dbname';
    public const FIELD_SUBDOMAIN = 'subdomain';
    public const FIELD_NAME = 'name';
    public const FIELD_UUID = 'uuid';

    /**
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function create(array $data): bool
    {
        if ($entity = $this->getBySubdomainDatabase(
            $data[static::FIELD_SUBDOMAIN] ?? null,
            $data[static::FIELD_DBNAME] ?? null
        )) {
            $entity->{static::FIELD_NAME} = $this->validated($data, $entity->id)[static::FIELD_NAME];
        } else {
            $entity = ShardCoordinator::create($this->validated($data));
        }

        return $entity->save();
    }

    public function getBySubdomainDatabase(?string $subdomain, ?string $dbname): null|ShardCoordinator
    {
        if (empty($subdomain) || empty($dbname)) {
            return null;
        }

        return ShardCoordinator::where(static::FIELD_SUBDOMAIN, $subdomain)
            ->where(static::FIELD_DBNAME, $dbname)
            ->first();
    }

    public function getBySubdomain(string $subdomain): null|ShardCoordinator
    {
        return ShardCoordinator::where(static::FIELD_SUBDOMAIN, $subdomain)->first();
    }

    public function getByUUID(string $uuid): null|ShardCoordinator
    {
        return ShardCoordinator::where(static::FIELD_UUID, $uuid)->first();
    }

    public function getByDbname(string $dbname): null|ShardCoordinator
    {
        return ShardCoordinator::where(static::FIELD_DBNAME, $dbname)->first();
    }

    /**
     * @throws ValidationException
     */
    protected function validated(array $data, int $id = null): array
    {
        return Validator::make($data, $this->rules($id))
            ->validated();
    }

    protected function rules(int $id = null): array
    {
        $unique = Rule::unique((new ShardCoordinator)->getConnectionTable());

        if ($id) {
            $unique->ignore($id);
        }

        return [
            static::FIELD_DBNAME => [
                'required',
                $unique,
                'max:255',
                'regex:/^[a-z][0-9a-z-_.]+[0-9a-z]$/i',
            ],
            static::FIELD_SUBDOMAIN => [
                'required',
                $unique,
                'max:255',
                'regex:/^[a-z][0-9a-z-_.]+[0-9a-z]$/i',
            ],
            static::FIELD_NAME => [
                'required',
                'max:255',
            ],
        ];
    }

}
