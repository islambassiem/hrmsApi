<?php

namespace App\Actions\College;

use App\Models\College;
use Illuminate\Support\Facades\Gate;

class ShowCollegeAction
{
    public function handle(College $college): College
    {
        Gate::authorize('view', $college);

        return $college->load(['branch', 'createdBy', 'updatedBy']);
    }
}
