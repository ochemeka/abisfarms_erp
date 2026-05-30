@extends('layouts.app')
@section('title', 'HR')
@section('page-title', 'HR Dashboard')
@section('page-subtitle', 'Staff, payroll & attendance')
@section('sidebar') @include('layouts.sidebars.hr') @endsection
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
            dark:border-gray-700 p-8 text-center">
    <div class="text-4xl mb-3">👥</div>
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">
        HR Dashboard
    </h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm">
        Staff profiles, attendance, shifts and payroll coming soon.
    </p>
</div>
@endsection