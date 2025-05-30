<?php

namespace App\Actions\V1\Entity;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UpdateEntityAction
{
    public function handle(Entity $entity, User $user, array $data): Entity
    {
        Gate::authorize('update_entity', Entity::class);
        $data['updated_by'] = $user->id;

        $entity->update($data);

        return $entity;
    }
}
