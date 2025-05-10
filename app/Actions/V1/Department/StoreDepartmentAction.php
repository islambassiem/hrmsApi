<?php

namespace App\Actions\V1\Department;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class StoreDepartmentAction
{
    public function handle(User $user, array $attributes)
    {
        // authorize
        Gate::authorize('create_department', Department::class);

        // create a model
        $attributes['created_by'] = $user->id;
        $department = Department::create($attributes);

        // return created model
        return $department->load('createdBy', 'updatedBy');
    }
}
