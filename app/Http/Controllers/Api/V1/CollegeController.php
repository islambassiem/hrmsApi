<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\College\IndexCollegeAction;
use App\Actions\V1\College\ShowCollegeAction;
use App\Actions\V1\College\StoreCollegeAction;
use App\Actions\V1\College\UpdateCollegeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCollegeRequest;
use App\Http\Requests\V1\UpdateCollegeRequest;
use App\Http\Resources\V1\CollegeResource;
use App\Models\College;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CollegeController extends Controller implements HasMiddleware
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
    public function index(IndexCollegeAction $action): JsonResponse
    {
        $colleges = $action->handle();

        if ($colleges->count() > 0) {
            return response()->json([
                'data' => CollegeResource::collection($colleges),
            ]);
        }

        return response()->json([
            'message' => 'No content',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollegeRequest $request, StoreCollegeAction $action)
    {
        $college = $action->handle($request->validated());

        return response()->json([
            'data' => CollegeResource::make($college),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(College $college, ShowCollegeAction $action)
    {
        $college = $action->handle($college);

        return response()->json([
            'data' => CollegeResource::make($college),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCollegeRequest $request, College $college, UpdateCollegeAction $action)
    {
        $college = $action->handle($request->validated(), $college);

        return response()->json([
            'data' => CollegeResource::make($college),
        ]);
    }
}
