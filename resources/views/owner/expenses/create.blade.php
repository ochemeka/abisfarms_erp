<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.expenses.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                ← Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💰 Record Expense
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200
                        dark:border-gray-700 p-6 space-y-6">

                <form method="POST"
                      action="{{ route('owner.expenses.store') }}"
                      enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Expense Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="e.g. Generator fuel refill"
                               class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                                      focus:outline-none focus:ring-2 focus:ring-bh-red/50
                                      @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category + Amount row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category"
                                    class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5
                                           bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-bh-red/50
                                           @error('category') border-red-500 @enderror">
                                <option value="">— Select Category —</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" @selected(old('category') === $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Amount (₦) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="amount"
                                   value="{{ old('amount') }}"
                                   min="0.01"
                                   step="0.01"
                                   placeholder="0.00"
                                   class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5
                                          bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                                          focus:outline-none focus:ring-2 focus:ring-bh-red/50
                                          @error('amount') border-red-500 @enderror">
                            @error('amount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Expense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="expense_date"
                               value="{{ old('expense_date', date('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                                      focus:outline-none focus:ring-2 focus:ring-bh-red/50
                                      @error('expense_date') border-red-500 @enderror">
                        @error('expense_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Description <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea name="description"
                                  rows="3"
                                  placeholder="Additional notes about this expense..."
                                  class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5
                                         bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                                         focus:outline-none focus:ring-2 focus:ring-bh-red/50 resize-none
                                         @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Receipt upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Receipt <span class="text-gray-400 font-normal">(optional · JPG, PNG or PDF, max 2MB)</span>
                        </label>
                        <input type="file"
                               name="receipt"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full text-sm text-gray-600 dark:text-gray-300
                                      file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-medium file:bg-bh-red/10 file:text-bh-red
                                      hover:file:bg-bh-red/20 cursor-pointer
                                      @error('receipt') border-red-500 @enderror">
                        @error('receipt')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-bh-red text-white text-sm font-medium rounded-lg
                                       hover:bg-red-700 transition-colors">
                            Submit Expense
                        </button>
                        <a href="{{ route('owner.expenses.index') }}"
                           class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-600
                                  dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50
                                  dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
