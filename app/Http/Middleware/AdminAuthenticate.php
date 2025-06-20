<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin area.');
        }
        
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'You do not have permission to access the admin area.');
        }
        
        return $next($request);
    }
}
