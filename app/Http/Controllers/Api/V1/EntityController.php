<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Entity\IndexEntityAction;
use App\Actions\V1\Entity\ShowEntityAction;
use App\Actions\V1\Entity\StoreEntityAction;
use App\Actions\V1\Entity\UpdateEntityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreEntityRequest;
use App\Http\Requests\V1\UpdateEntityRequest;
use App\Http\Resources\V1\EntityResource;
use App\Models\Entity;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexEntityAction $action)
    {
        $entities = $action->handle();

        if ($entities->count() > 0) {
            return EntityResource::collection($entities);
        }

        return response()->json([
            'message' => 'There is no content',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityRequest $request, StoreEntityAction $action)
    {
        $entity = $action->handle(Auth::user(), $request->validated());

        return EntityResource::make($entity);
    }

    /**
     * Display the specified resource.
     */
    public function show(Entity $entity, ShowEntityAction $action)
    {
        $entity = $action->handle($entity);

        return new EntityResource($entity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityRequest $request, Entity $entity, UpdateEntityAction $action)
    {
        $entity = $action->handle($entity, Auth::user(), $request->validated());

        return new EntityResource($entity);
    }
}
