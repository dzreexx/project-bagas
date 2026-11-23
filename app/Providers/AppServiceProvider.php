<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $unreadCount = ActivityLog::where('is_read', false)->count();
            $recentLogs = ActivityLog::latest()->take(8)->get();

            $view->with([
                'unreadNotificationCount' => $unreadCount,
                'recentActivityLogs' => $recentLogs,
            ]);
        });
    }
}
