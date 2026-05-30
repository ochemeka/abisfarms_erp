<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abis Farm Market LTD ERP — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bh-red':   '#C0392B',
                        'bh-dark':  '#7B241C',
                        'bh-light': '#FADBD8',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">

    {{-- Logo / Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16
                    bg-bh-red rounded-2xl mb-4 shadow-lg">
            <span class="text-white text-2xl font-bold">B</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Abis Farm Market LTD ERP</h1>
        <p class="text-gray-500 text-sm mt-1">
            Marketplace
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

        <h2 class="text-lg font-semibold text-gray-800 mb-1">Welcome back</h2>
        <p class="text-sm text-gray-500 mb-6">Sign in to your account</p>

        {{-- Session errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700
                        text-sm rounded-lg px-4 py-3 mb-5">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Session status (e.g. password reset) --}}
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700
                        text-sm rounded-lg px-4 py-3 mb-5">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" x-data="{ show: false }">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email address
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg
                           text-sm focus:outline-none focus:ring-2
                           focus:ring-bh-red focus:border-transparent
                           @error('email') border-red-400 @enderror"
                    placeholder="you@abisfarm.ng"
                >
            </div>

            {{-- Password --}}
            <div class="mb-5">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-bh-red hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg
                               text-sm focus:outline-none focus:ring-2
                               focus:ring-bh-red focus:border-transparent pr-10"
                        placeholder="••••••••"
                    >
                    <button type="button" @click="show = !show"
                            class="absolute right-3 top-2.5 text-gray-400
                                   hover:text-gray-600 text-xs">
                        <span x-text="show ? 'Hide' : 'Show'"></span>
                    </button>
                </div>
            </div>

            {{-- Remember me --}}
            <div class="flex items-center mb-6">
                <input type="checkbox" name="remember" id="remember"
                       class="w-4 h-4 text-bh-red border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-600">
                    Remember me for 30 days
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-bh-red hover:bg-bh-dark text-white
                           font-semibold py-2.5 rounded-lg text-sm
                           transition-colors duration-200">
                Sign In
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        &copy; {{ date('Y') }} Abis Farm Market LTD ERP &mdash; All rights reserved
    </p>

</div>
</body>
</html>