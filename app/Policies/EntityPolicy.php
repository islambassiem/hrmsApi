<?php

namespace App\Policies;

use App\Models\User;

class EntityPolicy
{
    public function access(User $user): bool
    {
        return $user->hasRole(['admin']);
    }
}
