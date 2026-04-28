<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('superadmin', 'admin', 'editor')) {
            abort(403, 'Acces reserve aux administrateurs.');
        }

        return $next($request);
    }
}
