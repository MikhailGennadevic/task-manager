<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Task::observe(\App\Observers\TaskObserver::class);
        TaskComment::observe(\App\Observers\TaskCommentObserver::class);
    }
}
