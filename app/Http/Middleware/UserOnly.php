<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->role !== 'user') {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}