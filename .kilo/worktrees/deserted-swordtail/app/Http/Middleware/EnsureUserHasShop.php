<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasShop
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Site-admin operates across all shops — no shop required
        if ($user->hasRole('site-admin')) {
            return $next($request);
        }

        // Check shop is assigned via session or user record
        $shopId = session('active_shop_id') ?? $user->shop_id;

        if (!$shopId) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'error',
                'Your account has no shop assigned. Please contact your administrator.'
            );
        }

        return $next($request);
    }
}
