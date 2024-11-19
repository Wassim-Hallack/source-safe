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
        return $this->logExecution(function () use ($request) {
            return $this->userService->register($request);
        }, __FUNCTION__, $request->all());
    }

    public function login(Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->userService->login($request);
        }, __FUNCTION__, $request->all());
    }

    public function refresh(Request $request)
    {
        return $this->logExecution(function () {
            return $this->userService->refresh();
        }, __FUNCTION__);
    }

    public function logout()
    {
        return $this->logExecution(function () {
            return $this->userService->logout();
        }, __FUNCTION__);
    }
}
