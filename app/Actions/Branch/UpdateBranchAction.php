<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UpdateBranchAction
{
    public function handle(User $user, Branch $branch, array $attributes): Branch
    {
        Gate::authorize('access', $branch);

        $branch->update($attributes);

        return $branch;
    }
}
