<?php

namespace App\Http\Controllers\Api;

use App\Actions\Entity\IndexEntityAction;
use App\Actions\Entity\ShowEntityAction;
use App\Actions\Entity\StoreEntityAction;
use App\Actions\Entity\UpdateEntityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
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
