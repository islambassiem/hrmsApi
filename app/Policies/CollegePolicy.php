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
        return $user->hasAllRoles(['admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, College $college): bool
    {
        return $user->hasAllRoles(['admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAllRoles(['admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, College $college): bool
    {
        return $user->hasAllRoles(['admin']);
    }
}
