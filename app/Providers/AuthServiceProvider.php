<?php

namespace App\Providers;
use App\Models\User;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('admin', function ($user) {
            return $user->role === User::ROLE_ADMIN;
        });

        // 一般管理者 Gate 規則
        Gate::define('manager', function ($user) {
            return $user->role === User::ROLE_MANAGER;
        });

        // 一般使用者 Gate 規則
        Gate::define('user', function ($user) {
            return $user->role === User::ROLE_USER;
        });
        //
    }
}
