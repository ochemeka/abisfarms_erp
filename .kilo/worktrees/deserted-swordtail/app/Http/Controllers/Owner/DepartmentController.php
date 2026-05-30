<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('staff')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $shops = Shop::where('is_active', true)
            ->select('id', 'name', 'type')
            ->get();

        return view('owner.departments.index',
            compact('departments', 'shops'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'description'    => ['nullable', 'string', 'max:255'],
            'color'          => ['required', 'string', 'max:20'],
            'accepts_orders' => ['boolean'],
            'shop_id'        => ['required', 'exists:shops,id'],
        ]);

        $validated['accepts_orders'] = $request->boolean('accepts_orders', true);
        $validated['sort_order'] = Department::where('shop_id', $validated['shop_id'])->count();

        Department::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->log("Created department: {$validated['name']}");

        return back()->with('success',
            "Department '{$validated['name']}' created.");
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'description'    => ['nullable', 'string', 'max:255'],
            'color'          => ['required', 'string', 'max:20'],
            'accepts_orders' => ['boolean'],
        ]);

        $validated['accepts_orders'] = $request->boolean('accepts_orders', true);

        $department->update($validated);

        return back()->with('success', 'Department updated.');
    }

    public function toggle(Department $department): RedirectResponse
    {
        $department->update(['is_active' => !$department->is_active]);
        $status = $department->is_active ? 'activated' : 'deactivated';
        return back()->with('success',
            "Department '{$department->name}' {$status}.");
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();
        return back()->with('success', 'Department removed.');
    }
}