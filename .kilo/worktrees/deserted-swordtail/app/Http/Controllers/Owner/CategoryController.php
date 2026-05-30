<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->latest()->get();

        return view('owner.inventory.categories', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        Category::create($validated);

        return back()->with('success', "Category '{$validated['name']}' created.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        $category->update($validated);

        return back()->with('success', "Category updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return back()->with('success', "Category removed.");
    }
}