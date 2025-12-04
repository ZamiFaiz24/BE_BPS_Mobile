<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Define gates for role-based access control
        Gate::define('view content', function (User $user) {
            // Both superadmin and operator can view content
            return $user->role === 'superadmin' || $user->role === 'operator';
        });

        Gate::define('view settings', function (User $user) {
            // Only superadmin can view settings
            return $user->role === 'superadmin';
        });

        Gate::define('manage users', function (User $user) {
            // Only superadmin can manage users
            return $user->role === 'superadmin';
        });
    }
}
