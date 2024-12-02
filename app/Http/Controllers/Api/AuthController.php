<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $expiredAt;

    public function __construct()
    {
        // set expired token
        $this->expiredAt = Carbon::now()->addHours(24);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token', ['*'], $this->expiredAt)->plainTextToken;

        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Berhasil register',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'token_expired' =>  $this->expiredAt->toDateTimeString(),
            ]
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Wrong email or password combination'
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token', ['*'], $this->expiredAt)->plainTextToken;


        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Login successful',
                'token' => $token,
                'token_type' => 'Bearer',
                'token_expired' =>  $this->expiredAt->toDateTimeString(),
            ]
        ], 200);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'code' => 200,
            'message' => 'logout success'
        ]);
    }
}
