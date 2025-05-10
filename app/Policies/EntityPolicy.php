<?php

namespace App\Policies;

use App\Models\Entity;
use App\Models\User;

class EntityPolicy
{
/**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_entity');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Entity $entity): bool
    {
        return $user->hasPermissionTo('view_entity', $entity);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_entity');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Entity $entity): bool
    {
        return $user->hasPermissionTo('update_entity', $entity);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Entity $entity): bool
    {
        return $user->hasPermissionTo('delete_entity', $entity);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Entity $entity): bool
    {
        return $user->hasPermissionTo('restore_entity', $entity);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Entity $entity): bool
    {
        return $user->hasPermissionTo('force_delete_entity', $entity);
    }
}
