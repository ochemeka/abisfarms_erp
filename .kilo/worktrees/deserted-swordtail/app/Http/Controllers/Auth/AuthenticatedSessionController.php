<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Check if user has any roles
        if (!$user->roles()->exists()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'You do not have permission to access this system.',
            ]);
        }

        // Update last login timestamp
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(
            $this->redirectBasedOnRole($user)
        );
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    private function redirectBasedOnRole(\App\Models\User $user): string
    {
        return match(true) {
            $user->hasRole('site-admin')    => route('admin.dashboard'),
            $user->hasRole('owner')         => route('owner.dashboard'),
            $user->hasRole('manager')       => route('manager.dashboard'),
            $user->hasRole('hr')            => route('hr.dashboard'),
            $user->hasRole('supervisor')    => route('supervisor.dashboard'),
            $user->hasRole('cashier')       => route('cashier.dashboard'),
            $user->hasRole('pos-attendant') => route('pos.dashboard'),
            default                         => route('dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}