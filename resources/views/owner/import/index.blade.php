@extends('layouts.app')
@section('title', 'Import & Export')
@section('page-title', 'Import & Export')
@section('page-subtitle', 'Upload data from Sage 50, Excel or CSV — and export to Excel or CSV')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('topbar-actions')
    <a href="{{ route('owner.import.index') }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white text-sm rounded-lg transition-colors">
        📥 Import / Export
    </a>
@endsection

@section('content')

{{-- Live counts --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Products',  'count' => $stats['products'],  'icon' => '📦', 'color' => 'text-emerald-500'],
        ['label' => 'Customers', 'count' => $stats['customers'], 'icon' => '👥', 'color' => 'text-blue-500'],
        ['label' => 'Suppliers', 'count' => $stats['suppliers'], 'icon' => '🏭', 'color' => 'text-purple-500'],
        ['label' => 'Sales',     'count' => $stats['sales'],     'icon' => '🧾', 'color' => 'text-amber-500'],
    ] as $s)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $s['icon'] }} {{ $s['label'] }} in system</p>
        <p class="text-2xl font-bold {{ $s['color'] }}">{{ number_format($s['count']) }}</p>
    </div>
    @endforeach
</div>

{{-- Success banner --}}
@if(session('import_success'))
@php $r = session('import_success'); @endphp
<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-6 flex gap-3 items-start">
    <span class="text-2xl">✅</span>
    <div class="flex-1">
        <p class="font-semibold text-green-800 dark:text-green-300">{{ $r['type'] }} imported successfully</p>
        <p class="text-sm text-green-700 dark:text-green-400 mt-0.5">
            <strong>{{ $r['created'] }}</strong> created &nbsp;·&nbsp;
            <strong>{{ $r['updated'] }}</strong> updated &nbsp;·&nbsp;
            <strong>{{ $r['skipped'] }}</strong> skipped
        </p>
        @if(!empty($r['errors']))
        <details class="mt-2 text-xs text-red-600 dark:text-red-400">
            <summary class="cursor-pointer font-medium">{{ count($r['errors']) }} warning(s) — click to expand</summary>
            <ul class="mt-1 list-disc pl-4 space-y-0.5">
                @foreach($r['errors'] as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </details>
        @endif
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6">
    <p class="font-semibold text-red-800 dark:text-red-300">Import Error</p>
    <p class="text-sm text-red-700 dark:text-red-400">{{ $errors->first('file') }}</p>
</div>
@endif

{{-- ══════════════════════════════════════════ --}}
{{-- 1. PRODUCTS                               --}}
{{-- ══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-xl">📦</span>
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white">Products / Inventory</h3>
                <p class="text-xs text-gray-400">Sage 50 inventory XLSX or custom CSV · columns: Item ID, Description, Item Class, Price Level 1, Last Unit Cost</p>
            </div>
        </div>
        <span class="text-xs font-medium text-emerald-700 bg-emerald-100 dark:bg-emerald-900/40 dark:text-emerald-300 px-3 py-1 rounded-full">
            {{ number_format($stats['products']) }} in system
        </span>
    </div>
    <div class="p-5 grid md:grid-cols-2 gap-6">
        {{-- Import --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↑ Import</p>
            <form action="{{ route('owner.import.products') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl p-5 text-center cursor-pointer hover:border-emerald-400 transition-colors"
                     onclick="document.getElementById('products-file').click()">
                    <input type="file" id="products-file" name="file" accept=".csv,.xlsx,.xls" class="hidden" required
                           onchange="document.getElementById('products-label').textContent = this.files[0]?.name || 'Drop file here or browse'">
                    <p class="text-gray-400 text-sm" id="products-label">Drop file here or <span class="text-emerald-600 font-medium">browse</span></p>
                    <p class="text-xs text-gray-400 mt-1">Accepts: .xlsx (Sage 50) · .csv</p>
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                    Import Products
                </button>
            </form>
        </div>
        {{-- Export --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↓ Export</p>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-300">Download all <strong>{{ number_format($stats['products']) }}</strong> products.</p>
                <p class="text-xs text-gray-400">Includes: SKU, name, category, price, cost, stock qty, unit</p>
                <div class="flex gap-2 pt-1">
                    <a href="{{ route('owner.export.products', ['format' => 'csv']) }}"
                       class="flex-1 text-center border border-gray-200 dark:border-gray-600 hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📄 CSV
                    </a>
                    <a href="{{ route('owner.export.products', ['format' => 'xlsx']) }}"
                       class="flex-1 text-center border border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📊 Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- 2. CUSTOMERS                              --}}
{{-- ══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-xl">👥</span>
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white">Customers</h3>
                <p class="text-xs text-gray-400">Upload your Sage 50 sales CSV — system extracts 135 unique customers automatically</p>
            </div>
        </div>
        <span class="text-xs font-medium text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 px-3 py-1 rounded-full">
            {{ number_format($stats['customers']) }} in system
        </span>
    </div>
    <div class="p-5 grid md:grid-cols-2 gap-6">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↑ Import</p>
            <form action="{{ route('owner.import.customers') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                {{-- Format toggle --}}
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="format" value="sage50" class="sr-only peer" checked>
                        <div class="border-2 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 border-gray-200 dark:border-gray-600 rounded-lg p-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 transition-all">
                            Sage 50 Sales CSV<br><span class="font-normal text-gray-400">(extracts customers)</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="format" value="generic" class="sr-only peer">
                        <div class="border-2 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 border-gray-200 dark:border-gray-600 rounded-lg p-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 transition-all">
                            Custom CSV/Excel<br><span class="font-normal text-gray-400">(name, phone, email)</span>
                        </div>
                    </label>
                </div>
                <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl p-5 text-center cursor-pointer hover:border-blue-400 transition-colors"
                     onclick="document.getElementById('customers-file').click()">
                    <input type="file" id="customers-file" name="file" accept=".csv,.xlsx,.xls" class="hidden" required
                           onchange="document.getElementById('customers-label').textContent = this.files[0]?.name || 'Drop file here or browse'">
                    <p class="text-gray-400 text-sm" id="customers-label">Drop file here or <span class="text-blue-600 font-medium">browse</span></p>
                    <p class="text-xs text-gray-400 mt-1">Your <em>customers_-_SALES.CSV</em> works here</p>
                </div>
                <div class="text-xs bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-lg p-3 text-amber-800 dark:text-amber-300">
                    💡 Your Sage 50 file has <strong>135 customers</strong> and <strong>266 products</strong>. Use "Sage 50 Sales CSV" to import customers from it.
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                    Import Customers
                </button>
            </form>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↓ Export</p>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-300">Download all <strong>{{ number_format($stats['customers']) }}</strong> customers.</p>
                <p class="text-xs text-gray-400">Includes: name, phone, email, loyalty points, total spent, debt</p>
                <div class="flex gap-2 pt-1">
                    <a href="{{ route('owner.export.customers', ['format' => 'csv']) }}"
                       class="flex-1 text-center border border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📄 CSV
                    </a>
                    <a href="{{ route('owner.export.customers', ['format' => 'xlsx']) }}"
                       class="flex-1 text-center border border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📊 Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- 3. SUPPLIERS                              --}}
{{-- ══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-xl">🏭</span>
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white">Suppliers / Vendors</h3>
                <p class="text-xs text-gray-400">Sage 50 vendor XLSX or custom CSV · your file has 31 vendors, 3 with outstanding balances</p>
            </div>
        </div>
        <span class="text-xs font-medium text-purple-700 bg-purple-100 dark:bg-purple-900/40 dark:text-purple-300 px-3 py-1 rounded-full">
            {{ number_format($stats['suppliers']) }} in system
        </span>
    </div>
    <div class="p-5 grid md:grid-cols-2 gap-6">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↑ Import</p>
            <form action="{{ route('owner.import.suppliers') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl p-5 text-center cursor-pointer hover:border-purple-400 transition-colors"
                     onclick="document.getElementById('suppliers-file').click()">
                    <input type="file" id="suppliers-file" name="file" accept=".csv,.xlsx,.xls" class="hidden" required
                           onchange="document.getElementById('suppliers-label').textContent = this.files[0]?.name || 'Drop file here or browse'">
                    <p class="text-gray-400 text-sm" id="suppliers-label">Drop file here or <span class="text-purple-600 font-medium">browse</span></p>
                    <p class="text-xs text-gray-400 mt-1">Accepts: .xlsx (Sage 50 vendor list) · .csv</p>
                </div>
                <div class="text-xs bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800 rounded-lg p-3 text-orange-800 dark:text-orange-300">
                    ⚠️ 3 vendors have outstanding balances totalling ₦246M+. These will import as <strong>credit</strong> payment terms.
                </div>
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                    Import Suppliers
                </button>
            </form>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↓ Export</p>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-300">Download all <strong>{{ number_format($stats['suppliers']) }}</strong> suppliers.</p>
                <p class="text-xs text-gray-400">Includes: name, contact, phone, payment terms, balances owed</p>
                <div class="flex gap-2 pt-1">
                    <a href="{{ route('owner.export.suppliers', ['format' => 'csv']) }}"
                       class="flex-1 text-center border border-gray-200 dark:border-gray-600 hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📄 CSV
                    </a>
                    <a href="{{ route('owner.export.suppliers', ['format' => 'xlsx']) }}"
                       class="flex-1 text-center border border-gray-200 dark:border-gray-600 hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📊 Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- 4. SALES HISTORY                          --}}
{{-- ══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-xl">🧾</span>
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white">Sales History</h3>
                <p class="text-xs text-gray-400">Sage 50 sales CSV · 73,478 rows · 82 invoices · ₦506M total revenue</p>
            </div>
        </div>
        <span class="text-xs font-medium text-amber-700 bg-amber-100 dark:bg-amber-900/40 dark:text-amber-300 px-3 py-1 rounded-full">
            {{ number_format($stats['sales']) }} in system
        </span>
    </div>
    <div class="p-5 grid md:grid-cols-2 gap-6">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↑ Import from Sage 50</p>
            <form action="{{ route('owner.import.sales') }}" method="POST" enctype="multipart/form-data" class="space-y-3"
                  onsubmit="document.getElementById('sales-btn').textContent = '⏳ Importing — please wait...'; document.getElementById('sales-btn').disabled = true;">
                @csrf
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 text-xs text-amber-800 dark:text-amber-300 space-y-1">
                    <p class="font-semibold">⚠️ Large file — may take 1–3 minutes</p>
                    <p>Do not close the browser. Already-imported invoices are skipped automatically.</p>
                    <p class="font-medium mt-1">Import Products & Customers first for best results.</p>
                </div>
                <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl p-5 text-center cursor-pointer hover:border-amber-400 transition-colors"
                     onclick="document.getElementById('sales-file').click()">
                    <input type="file" id="sales-file" name="file" accept=".csv" class="hidden" required
                           onchange="document.getElementById('sales-label').textContent = this.files[0]?.name || 'Drop Sage 50 CSV here or browse'">
                    <p class="text-gray-400 text-sm" id="sales-label">Drop Sage 50 CSV here or <span class="text-amber-600 font-medium">browse</span></p>
                    <p class="text-xs text-gray-400 mt-1">CSV only · Max 50MB</p>
                </div>
                <button type="submit" id="sales-btn"
                        class="w-full bg-amber-600 hover:bg-amber-700 disabled:opacity-60 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                    Import Sales History
                </button>
            </form>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">↓ Export with Date Filter</p>
            <form action="{{ route('owner.export.sales') }}" method="GET"
                  class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-300">Export <strong>{{ number_format($stats['sales']) }}</strong> sales with optional date range.</p>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 font-medium">From</label>
                        <input type="date" name="from"
                               class="mt-1 w-full text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-300 focus:border-amber-400 outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 font-medium">To</label>
                        <input type="date" name="to"
                               class="mt-1 w-full text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-300 focus:border-amber-400 outline-none">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" name="format" value="csv"
                            class="flex-1 border border-gray-200 dark:border-gray-600 hover:border-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📄 CSV
                    </button>
                    <button type="submit" name="format" value="xlsx"
                            class="flex-1 border border-gray-200 dark:border-gray-600 hover:border-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-700 dark:text-gray-300 text-sm font-medium py-2 rounded-lg transition-all">
                        📊 Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Template downloads --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">📋 Download blank import templates</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('owner.template.products') }}"
           class="flex items-center gap-2 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 rounded-lg px-4 py-3 text-xs text-gray-600 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition-all">
            📄 Products Template
        </a>
        <a href="{{ route('owner.template.customers') }}"
           class="flex items-center gap-2 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 rounded-lg px-4 py-3 text-xs text-gray-600 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition-all">
            📄 Customers Template
        </a>
        <a href="{{ route('owner.template.suppliers') }}"
           class="flex items-center gap-2 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 rounded-lg px-4 py-3 text-xs text-gray-600 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition-all">
            📄 Suppliers Template
        </a>
        <a href="{{ route('owner.template.sales') }}"
           class="flex items-center gap-2 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 rounded-lg px-4 py-3 text-xs text-gray-600 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition-all">
            📄 Sales Template
        </a>
    </div>
</div>

@endsection