<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check()) {
            abort(403, 'AKSES DITOLAK');
        }
    
        $userRole = strtolower(trim(auth()->user()->role));
        $requiredRole = strtolower(trim($role));
    
        if ($userRole !== $requiredRole) {
            abort(403, 'AKSES DITOLAK');
        }
    
        return $next($request);
    }
}    