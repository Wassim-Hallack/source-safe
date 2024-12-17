<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\UserService;
use App\Traits\LogExecutionTrait;

class UserController extends Controller
{
    use LogExecutionTrait;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->userService->register($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->login($request);
    }

    public function refresh()
    {
        return $this->userService->refresh();
    }

    public function logout()
    {
        return $this->userService->logout();
    }

    public function all_users()
    {
        return $this->userService->all_users();
    }
}
