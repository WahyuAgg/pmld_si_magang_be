<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input: gunakan email & password
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba login dengan username
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $request->user();

        // Buat token
        $token = $user->createToken('api-token')->plainTextToken;

        // Tentukan kapan access_token expired (misalnya 2 jam)
        $token_expired = Carbon::now()->addHours(2);

        // Simpan tanggal expired ke token terakhir
        $user->tokens->last()->update([
            'expires_at' => $token_expired,
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $token_expired,
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke semua token milik user
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
