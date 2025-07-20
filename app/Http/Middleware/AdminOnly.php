<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
