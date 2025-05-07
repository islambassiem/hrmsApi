<?php

namespace App\Actions\College;

use App\Models\College;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateCollegeAction
{
    public function handle(array $attributes, College $college)
    {
        Gate::authorize('update', $college);

        $attributes['updated_by'] = Auth::user()->id;

        $college->update($attributes);

        return $college;
    }
}
