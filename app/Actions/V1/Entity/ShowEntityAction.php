<?php

namespace App\Actions\V1\Entity;

use App\Models\Entity;
use Illuminate\Support\Facades\Gate;

class ShowEntityAction
{
    public function handle(Entity $entity): Entity
    {
        Gate::authorize('access', $entity);

        return $entity->load('createdBy', 'updatedBy');
    }
}
