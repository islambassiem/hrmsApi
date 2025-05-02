<?php

namespace App\Observers;

use App\Models\Entity;
use Illuminate\Support\Facades\Cache;

class EntityObserver
{
    /**
     * Handle the Entity "created" event.
     */
    public function created(Entity $entity): void
    {
        Cache::forget('entities');
    }

    /**
     * Handle the Entity "updated" event.
     */
    public function updated(Entity $entity): void
    {
        Cache::forget('entities');
    }
}
