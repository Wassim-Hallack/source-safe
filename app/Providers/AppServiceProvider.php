<?php

namespace App\Providers;

use App\Services\AddFileRequestService;
use App\Services\AddFileRequestToUserService;
use App\Services\Admin\AdminService;
use App\Services\FileOperationService;
use App\Services\FileService;
use App\Services\GroupInvitationService;
use App\Services\GroupService;
use App\Services\UserFileService;
use App\Services\UserGroupService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserService::class, function () {
            return new UserService();
        });

        $this->app->bind(GroupService::class, function () {
            return new GroupService();
        });

        $this->app->bind(UserGroupService::class, function () {
            return new UserGroupService();
        });

        $this->app->bind(GroupInvitationService::class, function () {
            return new GroupInvitationService();
        });

        $this->app->bind(FileService::class, function () {
            return new FileService();
        });

        $this->app->bind(UserFileService::class, function () {
            return new UserFileService();
        });

        $this->app->bind(AddFileRequestService::class, function () {
            return new AddFileRequestService();
        });

        $this->app->bind(AddFileRequestToUserService::class, function () {
            return new AddFileRequestToUserService();
        });

        $this->app->bind(FileOperationService::class, function () {
            return new FileOperationService();
        });

        $this->app->bind(AdminService::class, function () {
            return new AdminService();
        });

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
