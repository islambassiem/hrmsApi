<?php

namespace App\Actions\V1\Branch;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class IndexBranchAction
{
    public function handle(): Collection
    {
        Gate::authorize('view_any_branch', Branch::class);

        return Cache::rememberForever('entities', function () {
            return Branch::with('entity', 'createdBy', 'updatedBy')->get();
        });
    }
}
