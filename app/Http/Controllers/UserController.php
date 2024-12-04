<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\LogExecutionTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use LogExecutionTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        return $this->userService->register($request);
    }

    public function login(Request $request)
    {
        return $this->userService->login($request);
    }

    public function refresh(Request $request)
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
