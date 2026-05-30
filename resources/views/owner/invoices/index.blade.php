@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Create and manage invoices, receipts, quotes and proformas')

@php
    $rp = match(auth()->user()->getRoleNames()->first()) {
        'cashier'       => 'cashier',
        'pos-attendant' => 'pos',
        'supervisor'    => 'supervisor',
        'manager'       => 'manager',
        default         => 'owner',
    };
@endphp

@section('topbar-actions')
    <a href="{{ route("{$rp}.invoices.create") }}"
       class="px-4 py-2 bg-bh-red hover:bg-bh-dark text-white
              text-sm rounded-lg transition-colors">
        + New Invoice
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-5">
    @foreach([
        ['Total',    $stats['total'],   'gray'],
        ['Paid',     $stats['paid'],    'green'],
        ['Pending',  $stats['pending'], 'orange'],
        ['Overdue',  $stats['overdue'], 'red'],
        ['Revenue',  '₦'.number_format($stats['revenue'],2), 'blue'],
    ] as [$label, $value, $color])
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border
                border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
            {{ $label }}
        </p>
        <p class="text-xl font-bold
                  {{ $color === 'green'  ? 'text-green-600' :
                     ($color === 'red'   ? 'text-red-500' :
                     ($color === 'orange'? 'text-orange-500' :
                     ($color === 'blue'  ? 'text-blue-600' :
                      'text-gray-800 dark:text-white'))) }}">
            {{ $value }}
        </p>
    </div>
    @endforeach
</div>

{{-- Invoice table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border
            border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Number</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Client</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium
                               text-gray-500 dark:text-gray-400">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                           transition-colors">
                    <td class="px-5 py-3 font-mono text-xs font-semibold
                               text-gray-800 dark:text-white">
                        {{ $invoice->invoice_number }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                        $typeColors = [
                            'invoice'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'proforma' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                            'receipt'  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'quote'    => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                        ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                     {{ $typeColors[$invoice->type] ?? '' }}
                                     capitalize">
                            {{ $invoice->type }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">
                            {{ $invoice->client_name }}
                        </p>
                        @if($invoice->client_phone)
                        <p class="text-xs text-gray-400">
                            {{ $invoice->client_phone }}
                        </p>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            ₦{{ number_format($invoice->total_amount, 2) }}
                        </p>
                        @if($invoice->amount_paid > 0
                            && $invoice->amount_paid < $invoice->total_amount)
                        <p class="text-xs text-orange-500">
                            Paid: ₦{{ number_format($invoice->amount_paid, 2) }}
                        </p>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @php
                        $statusColors = [
                            'draft'     => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                            'sent'      => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'paid'      => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'partial'   => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                            'overdue'   => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'cancelled' => 'bg-gray-100 text-gray-500',
                        ];
                        $displayStatus = $invoice->isOverdue()
                            ? 'overdue' : $invoice->status;
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                     {{ $statusColors[$displayStatus] ?? '' }}
                                     capitalize">
                            {{ $displayStatus }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500
                               dark:text-gray-400">
                        {{ $invoice->issue_date->format('d M Y') }}
                        @if($invoice->due_date)
                        <p class="text-gray-400">
                            Due: {{ $invoice->due_date->format('d M Y') }}
                        </p>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1 justify-end">
                            <a href="{{ route("{$rp}.invoices.show", $invoice) }}"
                               class="text-xs px-2 py-1 border
                                      border-gray-200 dark:border-gray-600
                                      rounded-lg text-gray-600
                                      dark:text-gray-300
                                      hover:border-bh-red hover:text-bh-red
                                      transition-colors">
                                View
                            </a>
                            <a href="{{ route("{$rp}.invoices.pdf", $invoice) }}"
                               class="text-xs px-2 py-1 border
                                      border-gray-200 dark:border-gray-600
                                      rounded-lg text-gray-600
                                      dark:text-gray-300
                                      hover:border-bh-red hover:text-bh-red
                                      transition-colors">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        class="px-5 py-12 text-center">
                        <div class="text-3xl mb-2">🧾</div>
                        <p class="text-gray-500 dark:text-gray-400
                                  text-sm mb-3">
                            No invoices yet
                        </p>
                        <a href="{{ route("{$rp}.invoices.create") }}"
                           class="inline-block px-4 py-2 bg-bh-red
                                  text-white text-sm rounded-lg
                                  hover:bg-bh-dark transition-colors">
                            + Create First Invoice
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
