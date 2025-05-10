<?php

namespace App\Actions\V1\Entity;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class StoreEntityAction
{
    public function handle(User $user, array $data): Entity
    {
        Gate::authorize('create_entity', Entity::class);

        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        return Entity::create($data);
    }
}
