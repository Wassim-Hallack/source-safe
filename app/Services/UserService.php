<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function register($request)
    {
        $is_exist = UserRepository::existsByConditions(['email' => $request['email']]);
        if ($is_exist) {
            return response()->json([
                'status' => false,
                'response' => 'The email is not unique',
            ], 400);
        }

        $data = $request->all();

//        $image = $data['image'];
//        $ext = $image->getClientOriginalExtension();
//        $imageName = time() . '.' . $ext;
//        $image->move(public_path() . '/images/profilePhotos/', $imageName);

        $user_data['name'] = $data['name'];
        $user_data['email'] = $data['email'];
        $user_data['password'] = Hash::make($data['password']);
//        $user_data['image'] = $imageName;

        UserRepository::create($user_data);

        return response()->json([
            'status' => true,
            'response' => 'User registered successfully.',
        ]);
    }

    public function login($request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 400);
        }

        $user = Auth::user();

        return response()->json([
            'status' => true,
            'access_token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user_data' => $user
        ]);
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'status' => true,
                'access_token' => $newToken,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]);
        } catch (TokenInvalidException) {
            return response()->json(['status' => false, 'response' => 'Token is invalid'], 400);
        } catch (JWTException) {
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
            ]);
        } catch (Throwable) {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong.",
            ], 400);
        }
    }

    public function all_users()
    {
        $logged_in_user = Auth::user();
        $conditions = ['id' => ['!=', $logged_in_user['id']]];
        $users = UserRepository::findAllByConditions($conditions);

        return response()->json([
            'status' => true,
            'response' => $users
        ]);
    }
}
