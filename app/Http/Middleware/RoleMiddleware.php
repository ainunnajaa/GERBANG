<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: role:admin or role:guru or role:wali_murid
     */
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        $user = $request->user();

        if (!$user || $user->role !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
