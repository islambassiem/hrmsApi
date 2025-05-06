<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class IndexBranchAction
{
    public function handle(): LengthAwarePaginator
    {
        Gate::authorize('access', Branch::class);

        return Cache::rememberForever('entities', function () {
            return Branch::with('createdBy', 'updatedBy')->paginate();
        });
    }
}
