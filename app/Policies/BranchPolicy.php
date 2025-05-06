<?php

namespace App\Policies;

use App\Models\User;

class BranchPolicy
{
    public function access(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
