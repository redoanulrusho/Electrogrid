<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConsumerAuth
{
    /**
     * Ensure the web guard is authenticated AND the user has role = 'consumer'.
     * This prevents an admin session from accidentally accessing consumer routes
     * and provides a role-layer safeguard on top of the web guard check.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if (!$user || $user->role !== 'consumer') {
            return redirect()->route('login')
                ->with('error', 'Consumer access only. Please log in as a consumer.');
        }

        return $next($request);
    }
}
