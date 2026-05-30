@extends('layouts.app')
@section('title', 'POS')
@section('page-title', 'POS — Point of Sale')
@section('page-subtitle', 'Take orders & fire to kitchen')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
            dark:border-gray-700 p-8 text-center">
    <div class="text-4xl mb-3">🛒</div>
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">
        POS Dashboard
    </h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm">
        Full POS selling interface coming soon.
    </p>

    <div class="mt-6 flex justify-center gap-3">

        <a href="{{ route('pos.invoices.index') }}"
           class="px-5 py-2.5 border border-gray-200 dark:border-gray-600
                  text-gray-600 dark:text-gray-300 text-sm rounded-lg
                  hover:border-bh-red hover:text-bh-red transition-colors">
            🧾 Invoices
        </a>
        
        <a href="{{ route('pos.sell') }}"
           class="px-5 py-2.5 bg-bh-red hover:bg-bh-dark text-white
                  text-sm font-medium rounded-lg transition-colors">
            🛒 Start Selling
        </a>
        
    </div>
</div>
@endsection