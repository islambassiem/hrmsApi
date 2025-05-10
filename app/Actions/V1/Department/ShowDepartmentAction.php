<?php

namespace App\Actions\V1\Department;

use App\Models\Department;
use Illuminate\Support\Facades\Gate;

class ShowDepartmentAction
{
    public function handle(Department $department)
    {
        // authorize
        Gate::authorize('view_department', $department);

        // return a model with relationships
        return $department->load('createdBy', 'updatedBy');
    }
}
