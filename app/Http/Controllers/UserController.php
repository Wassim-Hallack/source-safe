<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'max:250'],
            'email' => ['required', 'email', 'max:250'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg,gif', 'max:10240'],
            'password' => ['required', 'max:250'],
            'password_confirmation' => ['required', 'same:password']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong.",
            ], 400);
        }

        $user = User::where('email', $data['email'])->first();

        if ($user === null) {
            $image = $data['image'];
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path() . '/images/profilePhotos/', $imageName);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'image' => $imageName,
            ]);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            return response()->json([
                'status' =>  true,
                'response' => $success['token']
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong.",
            ], 400);
        }
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'status' => true,
                'response' => $success,
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
