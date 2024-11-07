<?php

namespace App\Providers;

use App\Repositories\AddFileRequestRepository;
use App\Repositories\AddFileRequestToUserRepository;
use App\Repositories\FileRepository;
use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserFileRepository;
use App\Services\AddFileRequestService;
use App\Services\AddFileRequestToUserService;
use App\Services\FileService;
use App\Services\GroupInvitationService;
use App\Services\GroupService;
use App\Services\UserFileService;
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

        $this->app->bind(FileService::class, function ($app) {
            return new FileService($app->make(FileRepository::class));
        });

        $this->app->bind(UserFileService::class, function ($app) {
            return new UserFileService($app->make(UserFileRepository::class));
        });

        $this->app->bind(AddFileRequestService::class, function ($app) {
            return new AddFileRequestService($app->make(AddFileRequestRepository::class));
        });

        $this->app->bind(AddFileRequestToUserService::class, function ($app) {
            return new AddFileRequestToUserService($app->make(AddFileRequestToUserRepository::class));
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
