<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💰 Expenses
            </h2>
            <a href="{{ route('owner.expenses.create') }}"
               class="px-4 py-2 bg-bh-red text-white text-sm rounded-lg
                      hover:bg-red-700 transition-colors">
                + Record Expense
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                            dark:border-green-800 rounded-lg text-green-700 dark:text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        ₦{{ number_format($totals['pending'], 2) }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Approved</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        ₦{{ number_format($totals['approved'], 2) }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Rejected</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        ₦{{ number_format($totals['rejected'], 2) }}
                    </p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Status</label>
                        <select name="status"
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                                       bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <option value="">All Statuses</option>
                            <option value="pending"  @selected(request('status') === 'pending')>Pending</option>
                            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Category</label>
                        <select name="category"
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                                       bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @selected(request('category') === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">From</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                               class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                                      bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">To</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                               class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                                      bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    </div>
                    <button type="submit"
                            class="px-4 py-2 bg-bh-red text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['status', 'category', 'from', 'to']))
                        <a href="{{ route('owner.expenses.index') }}"
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-600
                                  dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50
                                  dark:hover:bg-gray-700 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($expenses->isEmpty())
                    <div class="p-12 text-center text-gray-400 dark:text-gray-500">
                        <p class="text-4xl mb-3">💰</p>
                        <p class="text-sm">No expenses found.</p>
                        <a href="{{ route('owner.expenses.create') }}"
                           class="mt-3 inline-block text-bh-red text-sm hover:underline">
                            Record your first expense
                        </a>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Title</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Submitted By</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Amount</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($expenses as $expense)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        {{ $expense->expense_date->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('owner.expenses.show', $expense) }}"
                                           class="font-medium text-gray-800 dark:text-gray-100 hover:text-bh-red">
                                            {{ $expense->title }}
                                        </a>
                                        @if($expense->receipt_path)
                                            <span class="ml-1 text-xs text-gray-400">📎</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                        {{ $categories[$expense->category] ?? $expense->category }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                        {{ $expense->submittedBy->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-gray-100 whitespace-nowrap">
                                        ₦{{ number_format($expense->amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($expense->isPending())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                         bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Pending
                                            </span>
                                        @elseif($expense->isApproved())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                         bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                         bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('owner.expenses.show', $expense) }}"
                                               class="text-xs text-gray-500 hover:text-bh-red transition-colors">
                                                View
                                            </a>
                                            @if($expense->isPending())
                                                <form method="POST"
                                                      action="{{ route('owner.expenses.approve', $expense) }}"
                                                      onsubmit="return confirm('Approve this expense?')">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-xs text-green-600 hover:text-green-800 transition-colors">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
