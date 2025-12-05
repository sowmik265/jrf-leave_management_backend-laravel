<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    // Registration method
    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'email' => $validator->errors()->get('email') ?: ['Please provide a valid email address or it might already be registered.'],
                    'password' => $validator->errors()->get('password') ?: ['Password must be at least 8 characters long and confirmed.']
                ]
            ], 422);
        }

        // Create a new user with pending status
        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending', // cannot login until admin approves
            'role' => 'user',
        ]);

        return response()->json(['message' => 'User registered successfully! Please wait for approval.'], 201);
    }

    // LOGIN (Sanctum session-based)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'email' => $validator->errors()->get('email') ?: ['Please provide a valid email address.'],
                    'password' => $validator->errors()->get('password') ?: ['Please provide your password.']
                ]
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized. Invalid credentials.'], 401);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // BLOCK PENDING USERS HERE
        if ($user->status === 'pending') {
            Auth::logout(); // destroy session
            return response()->json([
                'message' => 'Your account is not active yet. Please wait for admin approval.'
            ], 403);
        }

        // SUCCESSFUL LOGIN
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status
            ]
        ], 200);
    }

    // Logout method (session mode)
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
