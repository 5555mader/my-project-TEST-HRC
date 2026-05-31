<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // กำหนดสิทธิ์สำหรับเมนูผู้บริหาร
        Gate::define('access-executive-menu', function (User $user) {
            return $user->isCEO() || $user->isDirector() || $user->role === 'Super Admin';
        });
    }
}