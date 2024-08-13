<?php
namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            // Check user role and status
            if ($user->role == $role) {
                if ($user->status == "active") {
                    // User is active, proceed to the requested page (e.g., dashboard)
                    return $next($request);
                } elseif ($user->status == "inactive") {
                    // User is inactive, redirect to OTP verification page
                    return redirect('/otpverify');
                }
            }
        }
        // return redirect(RouteServiceProvider::OTP);
        
        return redirect('/login')->withErrors('You do not have access to this page.');
    }
}
