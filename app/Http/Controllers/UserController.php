<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\UserGroup;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
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

    public function logout()
    {
        return $this->userService->logout();
    }
}
