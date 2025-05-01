<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entites = Entity::with('createdBy', 'updatedBy')->get();

        return EntityResource::collection($entites);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Entity $entity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        //
    }
}
