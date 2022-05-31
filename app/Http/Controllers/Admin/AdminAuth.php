<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;


class AdminAuth extends Controller
{

    public function login(Request $request)
    {
        $rules = [
            'name' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        }

        $users = User::where('name', $request->name)->first();
        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username.',
            ]);
        }
        if (!Hash::check($request->password, $users->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password.',
            ]);
        }
        $token = $users->createToken('token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login Successfully',
            'token' => $token
        ]);
    }


    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
            'email' => ['required', 'string']
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);


        $token = $user->createToken('token');

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => "Register User Successfully",
                'token' => $token->plainTextToken,
                'user' => $user,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "Some Problem",
        ]);
    }
    public function getTokens(Request $request)
    {
        $users = $request->user();
        return response()->json([$users]);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
    }
}
