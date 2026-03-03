<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request and ensure the user is an admin.
     *
     * This middleware checks if the authenticated user has an admin role.
     * If the user is not logged in or is not an admin, a 403 Forbidden response is returned.
     *
     * @param  Request $request
     * @param  Closure(Request) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()?->isAdmin()) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }

}
