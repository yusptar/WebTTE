<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function($user){
            return $user->role == "admin";
        });

        Gate::define('admin-it', function($user){
            return $user->username == "20220294535";
        });

        Gate::define('perawat', function($user){
            return $user->role == "perawat";
        });

        Gate::define('petugas', function($user){
            return $user->role == "petugas";
        });

        Gate::define('ppa', function($user){
            return $user->role == "ppa";
        });
    }
}
