<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Example gate
        Gate::define('access-eoms', function ($user) {
            return $user->role === 0 || $user->role === 1; // Super Admin or Admin
        });




        Gate::define('qao-only', function (User $user) {
            return $user->hasRole('super-admin') || $user->hasRole('admin'); // Super Admin or Admin
        });

        Gate::define('superadmin-only', function (User $user) {
            return $user->hasRole('super-admin'); // Super Admin
        });

        Gate::define('admin-only', function (User $user) {
            return $user->hasRole('admin'); // Admin
        });

        Gate::define('campus-only', function (User $user) {
            return $user->hasRole('campus-dcc'); // Campus
        });

        Gate::define('process_owners_only', function (User $user) {
            return $user->hasRole('process-owner'); // Process Owners
        });

        Gate::define('superadmin-admin-only', function (User $user) {
            return $user->hasRole('super-admin') || $user->hasRole('admin'); // Super Admin or Admin
        });

        Gate::define('sendMessages', function (User $user, $group) {
            return $group->users->contains($user);
        });


    }
}
