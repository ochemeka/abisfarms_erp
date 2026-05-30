<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.expenses.index') }}"
                   class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    ← Back
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    💰 {{ $expense->title }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                @if($expense->isPending())
                    <a href="{{ route('owner.expenses.edit', $expense) }}"
                       class="px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-600
                              dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50
                              dark:hover:bg-gray-700 transition-colors">
                        Edit
                    </a>
                    <form method="POST"
                          action="{{ route('owner.expenses.destroy', $expense) }}"
                          onsubmit="return confirm('Delete this expense?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-3 py-2 border border-red-200 text-red-600 text-sm rounded-lg
                                       hover:bg-red-50 transition-colors">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Flash --}}
            @if(session('success'))
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                            dark:border-green-800 rounded-lg text-green-700 dark:text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Status Banner --}}
            @if($expense->isApproved())
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                            dark:border-green-800 rounded-lg flex items-center gap-3">
                    <span class="text-green-600 text-lg">✅</span>
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">
                            Approved by {{ $expense->approvedBy->name ?? '—' }}
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
                            Rejected by {{ $expense->approvedBy->name ?? '—' }}
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-400 mb-1">
                            {{ $expense->rejected_at?->format('d M Y, g:ia') }}
                        </p>
                        <p class="text-sm text-red-600 dark:text-red-400">
                            Reason: {{ $expense->rejection_reason }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Details Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 grid grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Amount</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            ₦{{ number_format($expense->amount, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Status</p>
                        @if($expense->isPending())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                         bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Pending
                            </span>
                        @elseif($expense->isApproved())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                         bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Approved
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                         bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Rejected
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Category</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ \App\Models\Expense::categories()[$expense->category] ?? $expense->category }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Date</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $expense->expense_date->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Submitted By</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $expense->submittedBy->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Submitted On</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $expense->created_at->format('d M Y, g:ia') }}
                        </p>
                    </div>
                    @if($expense->description)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Description</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $expense->description }}</p>
                        </div>
                    @endif
                    @if($expense->receipt_path)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Receipt</p>
                            @php $ext = pathinfo($expense->receipt_path, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <img src="{{ Storage::url($expense->receipt_path) }}"
                                     alt="Receipt"
                                     class="max-w-xs rounded-lg border border-gray-200 dark:border-gray-600">
                            @else
                                <a href="{{ Storage::url($expense->receipt_path) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 text-sm text-bh-red hover:underline">
                                    📄 View Receipt PDF
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Approve / Reject panel (owner only, pending only) --}}
            @if($expense->isPending())
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Approval Decision</h3>

                    <div class="flex gap-3 flex-wrap">
                        {{-- Approve --}}
                        <form method="POST" action="{{ route('owner.expenses.approve', $expense) }}"
                              onsubmit="return confirm('Approve this expense?')">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium
                                           rounded-lg hover:bg-green-700 transition-colors">
                                ✅ Approve Expense
                            </button>
                        </form>

                        {{-- Reject toggle --}}
                        <button type="button"
                                onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                                class="px-5 py-2.5 border border-red-300 text-red-600 text-sm font-medium
                                       rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            ❌ Reject Expense
                        </button>
                    </div>

                    {{-- Rejection reason form --}}
                    <form id="reject-form"
                          method="POST"
                          action="{{ route('owner.expenses.reject', $expense) }}"
                          class="hidden space-y-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Rejection Reason <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason"
                                      rows="3"
                                      placeholder="Explain why this expense is being rejected..."
                                      class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5
                                             bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                                             focus:outline-none focus:ring-2 focus:ring-red-300 resize-none
                                             @error('rejection_reason') border-red-500 @enderror">{{ old('rejection_reason') }}</textarea>
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
    </div>
</x-app-layout>
