<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::withCount('users')
            ->with('manager')
            ->latest()
            ->paginate(12);

        return view('owner.shops.index', compact('shops'));
    }

    public function create(): View
    {
        $managers = User::role('manager')
            ->where('is_active', true)
            ->select('id', 'name', 'email')
            ->get();

        return view('owner.shops.create', compact('managers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'type'       => ['required', 'in:restaurant,market,butchery,hybrid'],
            'address'    => ['nullable', 'string', 'max:500'],
            'city'       => ['nullable', 'string', 'max:100'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:255'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        $shop = Shop::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($shop)
            ->log("Created shop: {$shop->name}");

        return redirect()
            ->route('owner.shops.index')
            ->with('success', "Shop '{$shop->name}' created successfully.");
    }

    public function edit(Shop $shop): View
    {
        $managers = User::role('manager')
            ->where('is_active', true)
            ->select('id', 'name', 'email')
            ->get();

        return view('owner.shops.edit', compact('shop', 'managers'));
    }

    public function update(Request $request, Shop $shop): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'type'       => ['required', 'in:restaurant,market,butchery,hybrid'],
            'address'    => ['nullable', 'string', 'max:500'],
            'city'       => ['nullable', 'string', 'max:100'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:255'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        $shop->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($shop)
            ->log("Updated shop: {$shop->name}");

        return redirect()
            ->route('owner.shops.index')
            ->with('success', "Shop '{$shop->name}' updated successfully.");
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete(); // soft delete only

        activity()
            ->causedBy(auth()->user())
            ->performedOn($shop)
            ->log("Deleted shop: {$shop->name}");

        return redirect()
            ->route('owner.shops.index')
            ->with('success', "Shop '{$shop->name}' removed.");
    }

    public function toggle(Shop $shop): RedirectResponse
    {
        $shop->update(['is_active' => !$shop->is_active]);

        $status = $shop->is_active ? 'activated' : 'deactivated';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($shop)
            ->log("Shop {$status}: {$shop->name}");

        return back()->with('success', "Shop '{$shop->name}' {$status}.");
    }
}