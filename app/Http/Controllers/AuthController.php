<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users|alpha_dash',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $users = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'is_admin' => false,
        ]);

        $token = $users->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token,
        ], 201); 
    }

    public function login(Request $request)
        {
            $request->validate([
                'login' => 'required|string', 
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->login)
                ->orWhere('username', $request->login)
                ->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'login' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful.',
                'user' => $user,
                'token' => $token,
            ]);
        }
        
        public function logout(Request $request) {
            $user = $request->user();
            $usedBytes = $user->filles()->sum('file_size');

            return response()->json([
                'user' => $user,
                'storage' => [
                    'used_bytes' => $usedBytes,
                    'limit_bytes' => $user->is_admin ? null : 500 * 1024 * 1024,
                ],
            ]);
        }
}
