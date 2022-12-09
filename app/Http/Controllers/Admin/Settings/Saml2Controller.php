<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Saml2Request;
use App\Resources\Organisation\TenantResource;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Slides\Saml2\Models\Tenant;

class Saml2Controller extends Controller
{
    protected TenantResource $repository;

    public function __construct(TenantResource $repository)
    {
        $this->repository = $repository;
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->repository->all()
        );
    }

    public function view(mixed $key): JsonResponse
    {
        $entities = $this->repository->findByAnyIdentifier(is_numeric($key) ? intval($key) : $key);

        if ($entities->isEmpty()) {
            Message::get(404);
        }

        return response()->json($entities->first());
    }

    public function create(Saml2Request $request): void
    {
        $tenant = new Tenant(array_merge(
                $request->validated(),
                ['uuid' => Uuid::uuid4()]
            ) + [
                'metadata' => []
            ]);

        if (!$tenant->save()) {
            Message::get(400, null, ['Tenant cannot be saved.']);
        }

        Message::get(201);
    }

    public function update(Saml2Request $request, int $id): void
    {
        if (!$tenant = $this->repository->findById($id)) {
            Message::get(404);
        }

        if (!$tenant->fill($request->validated() + ['metadata' => []])->save()) {
            Message::get(400, null, ['Tenant cannot be saved.']);
        }

        Message::get(200);
    }

    public function delete(mixed $key): void
    {
        $entities = $this->repository->findByAnyIdentifier(is_numeric($key) ? intval($key) : $key);

        if ($entities->isEmpty()) {
            Message::get(404);
        }

        foreach ($entities as $entity) {
            $entity->forceDelete();
        }

        Message::get(200);
    }
}
