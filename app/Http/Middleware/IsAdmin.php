<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $hasAdminRole = $user->roles()
                             ->where('rol', 'admin') 
                             ->exists();

        if (! $hasAdminRole) {
            abort(403, 'Bu sayfaya erişim izniniz yok.');
        }
        return $next($request);
    }
}
