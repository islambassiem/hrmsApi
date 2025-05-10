<?php

namespace App\Actions\V1\Entity;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class IndexEntityAction
{
    public function handle(): Collection
    {
        Gate::authorize('view_any_entity', Entity::class);

        return Cache::rememberForever('entities', function () {
            return Entity::with('createdBy', 'updatedBy')->get();
        });
    }
}
