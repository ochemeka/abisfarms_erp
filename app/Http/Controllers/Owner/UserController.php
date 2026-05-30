<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'shop'])
            ->where('id', '!=', auth()->id()) // don't show yourself
            ->latest()
            ->paginate(15);

        $roles = Role::whereNotIn('name', ['site-admin'])
            ->pluck('name');

        $shops = Shop::where('is_active', true)
            ->select('id', 'name', 'type')
            ->get();

        return view('owner.users.index', compact('users', 'roles', 'shops'));
    }

    public function create(): View
    {
        $roles = Role::whereNotIn('name', ['site-admin'])
            ->pluck('name');

        $shops = Shop::where('is_active', true)
            ->select('id', 'name', 'type', 'city')
            ->get();

        return view('owner.users.create', compact('roles', 'shops'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'exists:roles,name'],
            'shop_id'  => ['nullable', 'exists:shops,id'],
            'scope'    => ['required', 'in:branch,regional,all'],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => Hash::make($validated['password']),
            'shop_id'   => $validated['shop_id'] ?? null,
            'scope'     => $validated['scope'],
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Created user: {$user->name} with role {$validated['role']}");

        return redirect()
            ->route('owner.users.index')
            ->with('success', "User '{$user->name}' created successfully.");
    }

    public function edit(User $user): View
    {
        $roles = Role::whereNotIn('name', ['site-admin'])
            ->pluck('name');

        $shops = Shop::where('is_active', true)
            ->select('id', 'name', 'type', 'city')
            ->get();

        return view('owner.users.edit', compact('user', 'roles', 'shops'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role'     => ['required', 'exists:roles,name'],
            'shop_id'  => ['nullable', 'exists:shops,id'],
            'scope'    => ['required', 'in:branch,regional,all'],
        ]);

        $user->update([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'] ?? null,
            'shop_id' => $validated['shop_id'] ?? null,
            'scope'   => $validated['scope'],
        ]);

        // Update password only if provided
        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        // Sync role
        $user->syncRoles([$validated['role']]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Updated user: {$user->name}");

        return redirect()
            ->route('owner.users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    public function suspend(User $user): RedirectResponse
    {
        // Prevent suspending yourself
        abort_if($user->id === auth()->id(), 403);

        $user->update(['is_active' => false]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Suspended user: {$user->name}");

        return back()->with('success', "'{$user->name}' has been suspended.");
    }

    public function restore(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Restored user: {$user->name}");

        return back()->with('success', "'{$user->name}' has been restored.");
    }
}