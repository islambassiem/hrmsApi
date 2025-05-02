<?php

namespace App\Models;

use App\Observers\EntityObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

#[ObservedBy(EntityObserver::class)]
class Entity extends Model
{
    /** @use HasFactory<\Database\Factories\EntityFactory> */
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'code',
        'created_by',
        'updated_by',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return Cache::rememberForever('entity-'.$value, function () use ($value) {
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->with('createdBy', 'updatedBy')->firstOrFail();
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
