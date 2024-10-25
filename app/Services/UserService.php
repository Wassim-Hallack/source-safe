<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $user_data['password'] = bcrypt($data['password']);
        $user_data['image'] = $imageName;

        $user = $this->userRepository->create($user_data);

        return response()->json([
            'status' => true,
            'response' => $user->createToken('MyApp')->plainTextToken,
        ], 200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            return response()->json([
                'status' => true,
                'response' => $user->createToken('MyApp')->plainTextToken,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong.",
            ], 400);
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
