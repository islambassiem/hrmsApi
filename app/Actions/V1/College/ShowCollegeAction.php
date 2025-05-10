<?php

namespace App\Actions\V1\College;

use App\Models\College;
use Illuminate\Support\Facades\Gate;

class ShowCollegeAction
{
    public function handle(College $college): College
    {
        Gate::authorize('view_college', $college);

        return $college->load(['branch', 'createdBy', 'updatedBy']);
    }
}
