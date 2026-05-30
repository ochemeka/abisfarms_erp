@extends('layouts.app')
@section('title', 'Expense — ' . $expense->title)
@section('page-title', $expense->title)
@section('page-subtitle', '₦' . number_format($expense->amount, 2))
@section('sidebar') @include('layouts.sidebars.manager') @endsection

@section('topbar-actions')
    <a href="{{ route('manager.expenses.index') }}"
       class="px-4 py-2 border border-gray-200 dark:border-gray-600
              text-gray-600 dark:text-gray-300 text-sm rounded-lg
              hover:border-bh-red hover:text-bh-red transition-colors">
        ← Expenses
    </a>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

    {{-- Flash --}}
    @if(session('success'))
    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                dark:border-green-800 rounded-lg text-green-700 dark:text-green-300 text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Status banner --}}
    @if($expense->isApproved())
    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                dark:border-green-800 rounded-lg flex items-center gap-3">
        <span class="text-green-600 text-lg">✅</span>
        <div>
            <p class="text-sm font-medium text-green-700 dark:text-green-300">
                Approved by {{ $expense->approvedBy?->name ?? '—' }}
            </p>
            <p class="text-xs text-green-600 dark:text-green-400">
                {{ $expense->approved_at?->format('d M Y, g:ia') }}
            </p>
        </div>
    </div>
    @elseif($expense->isRejected())
    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200
                dark:border-red-800 rounded-lg flex items-start gap-3">
        <span class="text-red-600 text-lg mt-0.5">❌</span>
        <div>
            <p class="text-sm font-medium text-red-700 dark:text-red-300">
                Rejected by {{ $expense->approvedBy?->name ?? '—' }}
            </p>
            <p class="text-xs text-red-600 dark:text-red-400 mb-1">
                {{ $expense->approved_at?->format('d M Y, g:ia') }}
            </p>
            @if($expense->notes)
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ $expense->notes }}
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Details card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-5">

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5">
            <div>
                <p class="text-xs text-gray-400 mb-1">Amount</p>
                <p class="text-2xl font-bold text-bh-red">
                    ₦{{ number_format($expense->amount, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Category</p>
                <p class="font-semibold text-gray-800 dark:text-white capitalize">
                    {{ str_replace('_', ' ', $expense->category) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Date</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $expense->expense_date->format('d M Y') }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Recorded by</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $expense->recordedBy?->name ?? '—' }}
                </p>
            </div>
            @if($expense->vendor)
            <div>
                <p class="text-xs text-gray-400 mb-1">Vendor</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $expense->vendor }}
                </p>
            </div>
            @endif
            <div>
                <p class="text-xs text-gray-400 mb-1">Status</p>
                @php
                $sc = [
                    'pending'  => 'bg-orange-100 text-orange-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
                @endphp
                <span class="px-2 py-1 rounded-full text-xs font-medium
                             capitalize {{ $sc[$expense->status] ?? '' }}">
                    {{ $expense->status }}
                </span>
            </div>
        </div>

        @if($expense->notes)
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-4 py-3 mb-4">
            <p class="text-xs text-gray-400 mb-1">Notes</p>
            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                {{ $expense->notes }}
            </p>
        </div>
        @endif

        @if($expense->receipt_path)
        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
            <p class="text-xs text-gray-400 mb-2">Receipt</p>
            @php $ext = strtolower(pathinfo($expense->receipt_path, PATHINFO_EXTENSION)); @endphp
            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ asset('storage/' . $expense->receipt_path) }}"
                     alt="Receipt"
                     class="w-full max-w-sm rounded-lg border border-gray-200 dark:border-gray-600">
            @else
                <a href="{{ asset('storage/' . $expense->receipt_path) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 text-sm text-bh-red hover:underline">
                    📄 View Receipt PDF
                </a>
            @endif
        </div>
        @endif
    </div>

    {{-- Approve / Reject panel --}}
    @if($expense->isPending())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
                dark:border-gray-700 p-5 space-y-4">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Approval Decision
        </h3>

        <div class="flex gap-3 flex-wrap">
            {{-- Approve --}}
            <form method="POST"
                  action="{{ route('manager.expenses.approve', $expense) }}"
                  onsubmit="return confirm('Approve this expense?')">
                @csrf
                <button type="submit"
                        class="px-5 py-2.5 bg-green-600 text-white text-sm
                               font-medium rounded-lg hover:bg-green-700
                               transition-colors">
                    ✓ Approve Expense
                </button>
            </form>

            {{-- Reject toggle --}}
            <button type="button"
                    onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                    class="px-5 py-2.5 border border-red-300 text-red-600
                           text-sm font-medium rounded-lg hover:bg-red-50
                           dark:hover:bg-red-900/20 transition-colors">
                ✗ Reject Expense
            </button>
        </div>

        {{-- Rejection reason form --}}
        <form id="reject-form"
              method="POST"
              action="{{ route('manager.expenses.reject', $expense) }}"
              class="hidden space-y-3 pt-3 border-t border-gray-100 dark:border-gray-700">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason"
                          rows="3"
                          placeholder="Explain why this expense is being rejected..."
                          class="w-full text-sm border border-gray-300 dark:border-gray-600
                                 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700
                                 text-gray-800 dark:text-gray-100
                                 focus:outline-none focus:ring-2 focus:ring-red-300
                                 resize-none @error('rejection_reason') border-red-500 @enderror">{{ old('rejection_reason') }}</textarea>
                @error('rejection_reason')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-red-600 text-white text-sm font-medium
                           rounded-lg hover:bg-red-700 transition-colors">
                Confirm Rejection
            </button>
        </form>
    </div>
    @endif

</div>
@endsection