<?php

namespace App\Actions\Entity;

use App\Models\Entity;
use Illuminate\Support\Facades\Gate;

class ShowEntityAction
{
    public function handle(Entity $entity)
    {
        Gate::authorize('access', $entity);

        return $entity;
    }
}
