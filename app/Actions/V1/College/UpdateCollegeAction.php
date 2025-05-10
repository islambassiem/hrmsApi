<?php

namespace App\Actions\V1\College;

use App\Models\College;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateCollegeAction
{
    public function handle(array $attributes, College $college)
    {
        Gate::authorize('update_college', $college);

        $attributes['updated_by'] = Auth::user()->id;

        $college->update($attributes);

        return $college;
    }
}
