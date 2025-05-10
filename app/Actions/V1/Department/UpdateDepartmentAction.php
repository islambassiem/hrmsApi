<?php

namespace App\Actions\V1\Department;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UpdateDepartmentAction
{
    public function handle(User $user, Department $department, array $attributes)
    {
        // authorize
        Gate::authorize('update_department', $department);

        // update resource
        $attributes['updated_by'] = $user->id;
        $department->update($attributes);

        // return updated resource
        return $department->load('createdBy', 'updatedBy');
    }
}
