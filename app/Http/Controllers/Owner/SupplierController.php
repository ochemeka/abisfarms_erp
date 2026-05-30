<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $shopId    = auth()->user()->shop_id;
        $suppliers = Supplier::where('shop_id', $shopId)
            ->orderBy('name')
            ->paginate(25);

        $totalOwed = Supplier::where('shop_id', $shopId)
            ->selectRaw('SUM(total_supplied - total_paid) as owed')
            ->value('owed') ?? 0;

        return view('owner.suppliers.index', compact('suppliers', 'totalOwed'));
    }

    public function create()
    {
        return view('owner.suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'phone2'           => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'address'          => 'nullable|string|max:500',
            'payment_terms'    => 'required|in:cash,credit',
            'credit_days'      => 'nullable|integer|min:0|max:365',
            'bank_name'        => 'nullable|string|max:100',
            'bank_account'     => 'nullable|string|max:50',
            'bank_account_name'=> 'nullable|string|max:255',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $data['shop_id']       = auth()->user()->shop_id;
        $data['total_supplied'] = 0;
        $data['total_paid']    = 0;
        $data['is_active']     = true;
        $data['credit_days']   = $data['credit_days'] ?? 0;

        Supplier::create($data);

        return redirect()->route('owner.suppliers.index')
            ->with('success', 'Supplier "' . $data['name'] . '" added successfully.');
    }

    public function edit(Supplier $supplier)
    {
        abort_if($supplier->shop_id !== auth()->user()->shop_id, 403);
        return view('owner.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        abort_if($supplier->shop_id !== auth()->user()->shop_id, 403);

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'phone2'            => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|string|max:500',
            'payment_terms'     => 'required|in:cash,credit',
            'credit_days'       => 'nullable|integer|min:0|max:365',
            'bank_name'         => 'nullable|string|max:100',
            'bank_account'      => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'notes'             => 'nullable|string|max:1000',
            'is_active'         => 'boolean',
        ]);

        $data['credit_days'] = $data['credit_days'] ?? 0;
        $data['is_active']   = $request->boolean('is_active');

        $supplier->update($data);

        return redirect()->route('owner.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        abort_if($supplier->shop_id !== auth()->user()->shop_id, 403);
        $supplier->delete();
        return redirect()->route('owner.suppliers.index')
            ->with('success', 'Supplier removed.');
    }

    public function toggle(Supplier $supplier)
    {
        abort_if($supplier->shop_id !== auth()->user()->shop_id, 403);
        $supplier->update(['is_active' => !$supplier->is_active]);
        return back()->with('success', 'Supplier status updated.');
    }
}
