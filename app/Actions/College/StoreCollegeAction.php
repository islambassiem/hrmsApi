<?php

namespace App\Actions\College;

use App\Models\College;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StoreCollegeAction
{
    public function handle(array $attributes): College
    {
        Gate::authorize('create', College::class);

        $attributes['created_by'] = Auth::user()->id;
        $attributes['updated_by'] = Auth::user()->id;

        return College::create($attributes);
    }
}
