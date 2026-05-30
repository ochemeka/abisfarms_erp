@extends('layouts.app')
@section('title', 'Add Shop')
@section('page-title', 'Add New Shop')
@section('page-subtitle', 'Create a new branch or location')
@section('sidebar') @include('layouts.sidebars.owner') @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border
                border-gray-200 dark:border-gray-700 p-6">

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                    dark:border-red-800 text-red-700 dark:text-red-400
                    text-sm rounded-lg px-4 py-3 mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('owner.shops.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Shop name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="e.g. Abis Farm Market LTD Ikeja">
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Branch type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" required
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">Select type...</option>
                            @foreach(\App\Models\Shop::TYPES as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('type') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                    </select>
                </div>

                {{-- Manager --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">
                        Assign manager
                    </label>
                    <select name="manager_id"
                            class="w-full px-4 py-2.5 border border-gray-300
                                   dark:border-gray-600 dark:bg-gray-700
                                   dark:text-white rounded-lg text-sm
                                   focus:outline-none focus:ring-2
                                   focus:ring-bh-red">
                        <option value="">No manager yet</option>
                        @foreach($managers as $manager)
                        <option value="{{ $manager->id }}"
                            {{ old('manager_id')==$manager->id?'selected':'' }}>
                            {{ $manager->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- City --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="e.g. Lagos">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="08012345678">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red"
                           placeholder="branch@abisfarm.ng">
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700
                                  dark:text-gray-300 mb-1">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300
                                     dark:border-gray-600 dark:bg-gray-700
                                     dark:text-white rounded-lg text-sm
                                     focus:outline-none focus:ring-2
                                     focus:ring-bh-red"
                              placeholder="Full street address">{{ old('address') }}</textarea>
                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-4 border-t
                        border-gray-100 dark:border-gray-700">
                <button type="submit"
                        class="px-6 py-2.5 bg-bh-red hover:bg-bh-dark
                               text-white text-sm font-medium rounded-lg
                               transition-colors">
                    Create Shop
                </button>
                <a href="{{ route('owner.shops.index') }}"
                   class="px-6 py-2.5 border border-gray-200
                          dark:border-gray-600 text-gray-600
                          dark:text-gray-300 text-sm rounded-lg
                          hover:border-gray-400 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection