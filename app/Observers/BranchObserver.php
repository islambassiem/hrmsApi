<?php

namespace App\Observers;

use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class BranchObserver
{
    /**
     * Handle the Entity "created" event.
     */
    public function created(Branch $branch): void
    {
        Cache::forget('branches');
    }

    /**
     * Handle the Entity "updated" event.
     */
    public function updated(Branch $branch): void
    {
        Cache::forget('branches');
    }
}
