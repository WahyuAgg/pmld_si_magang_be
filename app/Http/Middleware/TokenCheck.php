<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TokenCheck
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $token = $user?->currentAccessToken();

        // Cek apakah token sudah kedaluwarsa
        if ($token && $token->expires_at && $token->expires_at < Carbon::now()) {
            // Hapus semua token milik user
            $user->tokens->each(function ($t) {
                $t->delete();
            });

            return response()->json(['message' => 'Token expired, silakan login kembali.'], 401);
        }

        // Tika token masih berlaku
        // Lanjutkan request
        $response = $next($request);

        // Update waktu terakhir digunakan dan perpanjang masa aktif token
        if ($token) {
            $token->forceFill([
                'last_used_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addHours(2)
            ])->save();
        }

        return $response;
    }
}
