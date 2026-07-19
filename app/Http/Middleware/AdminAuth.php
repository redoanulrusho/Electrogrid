<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     * Ensures the admin guard session is active before allowing access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login')->with(
                'error',
                'Grid Operator session expired. Re-authenticate to continue.'
            );
        }

        return $next($request);
    }
}
