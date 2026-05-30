@extends('layouts.app')
@section('title', 'Site Admin')
@section('page-title', 'Site Admin Dashboard')
@section('page-subtitle', 'Platform control — all tenants')
@section('sidebar') @include('layouts.sidebars.admin') @endsection
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
            dark:border-gray-700 p-8 text-center">
    <div class="text-4xl mb-3">🔧</div>
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">
        Site Admin Dashboard
    </h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm">
        Full platform control. Shops, billing, system config coming soon.
    </p>
</div>
@endsection