<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopSettingsController extends Controller
{
    public function edit(): View
    {
        $shop = $this->getActiveShop();
        return view('owner.settings.shop', compact('shop'));
    }

    public function update(Request $request): RedirectResponse
    {
        $shop = $this->getActiveShop();

       $validated = $request->validate([
            'name'               => ['nullable', 'string', 'max:255'],
            'tagline'            => ['nullable', 'string', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'email'              => ['nullable', 'email'],
            'address'            => ['nullable', 'string', 'max:255'],
            'address_full'       => ['nullable', 'string', 'max:500'],
            'city'               => ['nullable', 'string', 'max:100'],
            'bank_name'          => ['nullable', 'string', 'max:100'],
            'bank_account'       => ['nullable', 'string', 'max:50'],
            'bank_account_name'  => ['nullable', 'string', 'max:100'],
            'invoice_prefix'     => ['nullable', 'string', 'max:10'],
            'invoice_footer'     => ['nullable', 'string', 'max:500'],
            'default_tax_rate'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'logo'               => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file     = $request->file('logo');
            $filename = 'shop_' . $shop->id . '_' . time()
                        . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logos'), $filename);
            $validated['logo_path'] = 'uploads/logos/' . $filename;

            // Delete old logo
            if ($shop->logo_path
                && file_exists(public_path($shop->logo_path))) {
                unlink(public_path($shop->logo_path));
            }
        }

        unset($validated['logo']);
        $shop->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($shop)
            ->log("Shop settings updated: {$shop->name}");

        return back()->with('success', 'Shop settings saved.');
    }

    private function getActiveShop(): Shop
    {
        $shopId = session('active_shop_id')
            ?? auth()->user()->shop_id;
        return Shop::findOrFail($shopId);
    }
}