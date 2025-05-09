<?php

namespace App\Actions\V1\Branch;

use App\Models\Branch;
use Illuminate\Support\Facades\Gate;

class ShowBranchAction
{
    public function handle(Branch $branch)
    {
        Gate::authorize('access', $branch);

        return $branch->load(['entity.createdBy', 'entity.updatedBy', 'createdBy', 'updatedBy']);
    }
}
