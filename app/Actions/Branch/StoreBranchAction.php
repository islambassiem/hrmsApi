<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class StoreBranchAction
{
    public function handle(User $user, array $attributes): Branch
    {
        Gate::authorize('access', Branch::class);

        $attributes['created_by'] = $user->id;
        $attributes['updated_by'] = $user->id;

        return Branch::create($attributes);
    }
}
