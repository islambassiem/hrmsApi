<?php

namespace App\Actions\Entity;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UpdateEntityAction
{
    public function handle(Entity $entity, User $user, array $data)
    {
        Gate::authorize('access', Entity::class);
        $data['updated_by'] = $user->id;

        $entity->update($data);

        return $entity;
    }
}
