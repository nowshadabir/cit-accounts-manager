<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanEditAccounts
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->canEditAccounts()) {
            abort(403, 'Access denied. You have view-only access and cannot add, edit, or delete accounting records.');
        }

        return $next($request);
    }
}
