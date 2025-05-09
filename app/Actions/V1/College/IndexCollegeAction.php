<?php

namespace App\Actions\V1\College;

use App\Models\College;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class IndexCollegeAction
{
    public function handle(): Collection
    {
        Gate::authorize('viewAny', College::class);

        return Cache::rememberForever('colleges', function () {
            return College::with('branch', 'createdBy', 'updatedBy')->get();
        });
    }
}
