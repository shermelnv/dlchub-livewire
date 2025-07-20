<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
