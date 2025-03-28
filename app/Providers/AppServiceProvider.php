<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

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
        Gate::policy(Task::class,TaskPolicy::class);

        RateLimiter::for('api',function (Request $request){
            return Limit::perMinute(50)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json(['message' => 'Too many requests! Please try again later.'], 429);
                });
        });
    }
}
