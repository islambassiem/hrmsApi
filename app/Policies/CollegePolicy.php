<?php

namespace App\Policies;

use App\Models\College;
use App\Models\User;

class CollegePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_college');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, College $college): bool
    {
        return $user->hasPermissionTo('view_college', $college);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_college');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, College $college): bool
    {
        return $user->hasPermissionTo('update_college', $college);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, College $college): bool
    {
        return $user->hasPermissionTo('delete_college', $college);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, College $college): bool
    {
        return $user->hasPermissionTo('restore_college', $college);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, College $college): bool
    {
        return $user->hasPermissionTo('force_delete_college', $college);
    }
}
