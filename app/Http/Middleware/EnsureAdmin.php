<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->is_admin || ! $user->is_active) {
            if ($request->expectsJson()) return response()->json(['message' => 'Admin access is required.'], 403);
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
