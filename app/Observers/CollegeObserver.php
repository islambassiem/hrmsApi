<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class CollegeObserver
{
    public function created(): void
    {
        Cache::forget('colleges');
    }

    public function updated(): void
    {
        Cache::forget('colleges');
    }
}
