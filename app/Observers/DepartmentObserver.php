<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class DepartmentObserver
{
    public function created(): void
    {
        Cache::forget('departments');
    }

    public function updated(): void
    {
        Cache::forget('departments');
    }
}
