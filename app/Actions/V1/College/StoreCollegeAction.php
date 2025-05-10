<?php

namespace App\Actions\V1\College;

use App\Models\College;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StoreCollegeAction
{
    public function handle(array $attributes): College
    {
        Gate::authorize('create_college', College::class);

        $attributes['created_by'] = Auth::user()->id;
        $attributes['updated_by'] = Auth::user()->id;

        return College::create($attributes);
    }
}
