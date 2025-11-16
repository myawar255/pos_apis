<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'message' => 'Missing bearer token.',
            ], 401);
        }

        $tokenModel = PersonalAccessToken::with('tokenable')
            ->where('token', hash('sha256', $token))
            ->first();

        if (! $tokenModel || ! $tokenModel->tokenable || $tokenModel->isExpired()) {
            return response()->json([
                'message' => 'Invalid or expired token.',
            ], 401);
        }

        if (! empty($abilities)) {
            $tokenAbilities = $tokenModel->abilities ?? [];
            if (! in_array('*', $tokenAbilities, true)) {
                foreach ($abilities as $ability) {
                    if (! in_array($ability, $tokenAbilities, true)) {
                        return response()->json([
                            'message' => 'Token does not have the required ability.',
                        ], 403);
                    }
                }
            }
        }

        $tokenModel->forceFill(['last_used_at' => now()])->save();

        $user = $tokenModel->tokenable;
        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
