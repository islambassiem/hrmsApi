<?php

namespace App\Actions\V1\Branch;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class StoreBranchAction
{
    public function handle(User $user, array $attributes): Branch
    {
        Gate::authorize('access', Branch::class);

        $attributes['updated_by'] = $user->id;

        return Branch::create($attributes);
    }
}
