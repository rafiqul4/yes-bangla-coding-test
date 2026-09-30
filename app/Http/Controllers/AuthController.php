<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate(['email' => ['required','email'], 'password' => ['required','string']]);
        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) return response()->json(['message' => 'Invalid credentials.'], 422);
        $user->tokens()->delete();
        return response()->json(['token' => $user->createToken('northstar-web')->plainTextToken, 'user' => $user]);
    }

    public function logout(Request $request): JsonResponse { $request->user()->currentAccessToken()?->delete(); return response()->json(['message' => 'Logged out.']); }
    public function me(Request $request): JsonResponse { return response()->json(['user' => $request->user()]); }
}