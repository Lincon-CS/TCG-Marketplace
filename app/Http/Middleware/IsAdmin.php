<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and has the admin flag set to true
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized. Admin access only.');
        }

        return $next($request);
    }
}