<?php

namespace App\Models;

use App\Observers\BranchObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

#[ObservedBy(BranchObserver::class)]
class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name_en',
        'name_ar',
        'entity_id',
        'created_by',
        'updated_by',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return Cache::rememberForever('branch-'.$value, function () use ($value) {
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->with('createdBy', 'updatedBy')->firstOrFail();
        });
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function colleges(): HasMany
    {
        return $this->hasMany(College::class);
    }
}
