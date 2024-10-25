<?php

namespace App\Providers;

use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use App\Services\GroupInvitationService;
use App\Services\GroupService;
use App\Services\UserGroupService;
use Illuminate\Support\ServiceProvider;
use App\Services\UserService;
use App\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepository::class));
        });

        $this->app->bind(GroupService::class, function ($app) {
            return new GroupService($app->make(GroupRepository::class));
        });

        $this->app->bind(UserGroupService::class, function ($app) {
            return new UserGroupService($app->make(GroupRepository::class));
        });

        $this->app->bind(GroupInvitationService::class, function ($app) {
            return new GroupInvitationService($app->make(GroupInvitationRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
