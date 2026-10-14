<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() && $request->bearerToken()) {
            $token = ApiToken::with('user')->where('token', hash('sha256', $request->bearerToken()))->first();
            if ($token && $token->user?->is_active && (! $token->expires_at || $token->expires_at->isFuture())) {
                $token->forceFill(['last_used_at' => now()])->save();
                $request->setUserResolver(fn () => $token->user);
                $request->attributes->set('api_token', $token);
            }
        }

        if (! $request->user()) {
            return response()->json(['message' => 'Authentication is required.'], 401);
        }

        return $next($request);
    }
}
