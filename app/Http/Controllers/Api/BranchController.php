<?php

namespace App\Http\Controllers\Api;

use App\Actions\Branch\IndexBranchAction;
use App\Actions\Branch\ShowBranchAction;
use App\Actions\Branch\StoreBranchAction;
use App\Actions\Branch\UpdateBranchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexBranchAction $action): JsonResponse
    {
        $branches = $action->handle();
        if ($branches->count() > 0) {
            return response()->json([
                'data' => BranchResource::collection($branches),
            ]);
        }

        return response()->json([
            'message' => 'There is no content',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request, StoreBranchAction $action): JsonResponse
    {
        $branch = $action->handle(Auth::user(), $request->validated());

        return response()->json([
            BranchResource::make($branch),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch, ShowBranchAction $action): JsonResponse
    {
        $branch = $action->handle($branch);

        return response()->json([
            'data' => BranchResource::make($branch),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch, UpdateBranchAction $action): JsonResponse
    {
        $branch = $action->handle(Auth::user(), $branch, $request->validated());

        return response()->json([
            'data' => BranchResource::make($branch),
        ]);
    }
}
