<?php

namespace App\Actions\V1\Department;

use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class IndexDepartmentAction
{
    public function handle(): Collection
    {
        Gate::authorize('view_any_department', Department::class);

        return Cache::rememberForever('departments', function () {
            return Department::with('createdBy', 'updatedBy')->get();
        });
    }
}
