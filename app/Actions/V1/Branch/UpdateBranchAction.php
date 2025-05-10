<?php

namespace App\Actions\V1\Branch;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UpdateBranchAction
{
    public function handle(User $user, Branch $branch, array $attributes): Branch
    {
        Gate::authorize('update_branch', $branch);

        $attributes['updated_by'] = $user->id;

        $branch->update($attributes);

        return $branch;
    }
}
