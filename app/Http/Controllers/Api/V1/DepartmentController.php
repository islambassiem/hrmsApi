<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Department\IndexDepartmentAction;
use App\Actions\V1\Department\ShowDepartmentAction;
use App\Actions\V1\Department\StoreDepartmentAction;
use App\Actions\V1\Department\UpdateDepartmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreDepartmentRequest;
use App\Http\Requests\V1\UpdateDepartmentRequest;
use App\Http\Resources\V1\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(['auth:sanctum']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexDepartmentAction $action): JsonResponse
    {
        $resource = $action->handle();

        if ($resource->count() > 0) {
            return response()->json([
                'status' => 'success',
                'data' => DepartmentResource::collection($resource),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'No content',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request, StoreDepartmentAction $action): JsonResponse
    {
        $resource = $action->handle(Auth::user(), $request->validated());

        return response()->json([
            'status' => 'success',
            'data' => DepartmentResource::make($resource),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department, ShowDepartmentAction $action): JsonResponse
    {
        $resource = $action->handle($department);

        return response()->json([
            'status' => 'success',
            'data' => DepartmentResource::make($resource),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department, UpdateDepartmentAction $action): JsonResponse
    {
        $resource = $action->handle(Auth::user(), $department, $request->validated());

        return response()->json([
            'status' => 'success',
            'data' => DepartmentResource::make($resource),
        ]);
    }
}
