<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
    'name'     => ['required', 'string', 'max:255'],
    'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'phone'    => ['nullable', 'string', 'max:20'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

       $user = User::create([
    'name'     => $request->name,
    'email'    => $request->email,
    'phone'    => $request->phone,
    'password' => Hash::make($request->password),
    'is_active'=> true,
    'scope'    => 'branch',
]);

// Assign default role for self-registered users
$user->assignRole('pos-attendant');


        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(
    $this->redirectBasedOnRole($user)
);
    }

    private function redirectBasedOnRole(User $user): string
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

}
