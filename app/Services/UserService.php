<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'max:250'],
            'email' => ['required', 'email', 'max:250', 'unique:users,email'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg,gif', 'max:10240'],
            'password' => ['required', 'max:250'],
            'password_confirmation' => ['required', 'same:password']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'response' => 'There is something wrong with some fields.',
            ], 400);
        }


        $image = $data['image'];
        $ext = $image->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $image->move(public_path() . '/images/profilePhotos/', $imageName);

        $user_data['name'] = $data['name'];
        $user_data['email'] = $data['email'];
        $user_data['password'] = Hash::make($data['password']);
        $user_data['image'] = $imageName;

        $this->userRepository->create($user_data);

        return response()->json([
            'status' => true,
            'response' => 'User registered successfully.',
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status' => true,
            'access_token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user_data' => $user
        ], 200);
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'status' => true,
                'access_token' => $newToken,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => false, 'response' => 'Token is invalid'], 400);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'response' => 'Token is required'], 400);
        }
    }

    public function logout()
    {
        try {
            Auth::user()->tokens()->delete();
            return response()->json([
                'status' => true,
                'response' => "Logged out successfully.",
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong.",
            ], 400);
        }
    }
}
