<!DOCTYPE html>
<html lang="en" x-data="themeManager()" :class="dark ? 'dark' : ''" x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abis Farm Market LTD ERP — Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: {
                'bh-red': '#C0392B', 'bh-dark': '#7B241C', 'bh-light': '#FADBD8'
            }}}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center
             justify-center transition-colors duration-200">

<div class="w-full max-w-md px-6 py-10">

    {{-- Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16
                    bg-bh-red rounded-2xl mb-4 shadow-lg">
            <span class="text-white text-2xl font-bold">B</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Create your account
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
            Abis Farm Market LTD ERP 
        </p>
    </div>

    {{-- Theme toggle --}}
    <div class="flex justify-center mb-4">
        <button @click="toggleTheme()"
                class="text-sm text-gray-400 hover:text-gray-600
                       dark:hover:text-gray-200 transition-colors">
            <span x-text="dark ? '☀️ Light mode' : '🌙 Dark mode'"></span>
        </button>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                border border-gray-200 dark:border-gray-700 p-8">

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200
                        dark:border-red-800 text-red-700 dark:text-red-400
                        text-sm rounded-lg px-4 py-3 mb-5">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}"
              x-data="{ show: false }">
            @csrf

            {{-- Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">Full name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       required autofocus
                       class="w-full px-4 py-2.5 border border-gray-300
                              dark:border-gray-600 dark:bg-gray-700
                              dark:text-white rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-bh-red
                              focus:border-transparent"
                       placeholder="e.g. Emeka Nwosu">
            </div>

            {{-- Phone --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">Phone number</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       class="w-full px-4 py-2.5 border border-gray-300
                              dark:border-gray-600 dark:bg-gray-700
                              dark:text-white rounded-lg text-sm
                              focus:outline-none focus:ring-2
                              focus:ring-bh-red focus:border-transparent"
                       placeholder="08012345678">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       required
                       class="w-full px-4 py-2.5 border border-gray-300
                              dark:border-gray-600 dark:bg-gray-700
                              dark:text-white rounded-lg text-sm
                              focus:outline-none focus:ring-2
                              focus:ring-bh-red focus:border-transparent"
                       placeholder="you@example.com">
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'"
                           name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700
                                  dark:text-white rounded-lg text-sm
                                  focus:outline-none focus:ring-2
                                  focus:ring-bh-red focus:border-transparent pr-10"
                           placeholder="Min. 8 characters">
                    <button type="button" @click="show = !show"
                            class="absolute right-3 top-2.5 text-xs
                                   text-gray-400 hover:text-gray-600">
                        <span x-text="show ? 'Hide' : 'Show'"></span>
                    </button>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700
                              dark:text-gray-300 mb-1">
                    Confirm password
                </label>
                <input :type="show ? 'text' : 'password'"
                       name="password_confirmation" required
                       class="w-full px-4 py-2.5 border border-gray-300
                              dark:border-gray-600 dark:bg-gray-700
                              dark:text-white rounded-lg text-sm
                              focus:outline-none focus:ring-2
                              focus:ring-bh-red focus:border-transparent"
                       placeholder="Repeat password">
            </div>

            <button type="submit"
                    class="w-full bg-bh-red hover:bg-bh-dark text-white
                           font-semibold py-2.5 rounded-lg text-sm
                           transition-colors duration-200">
                Create account
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                Already have an account?
                <a href="{{ route('login') }}"
                   class="text-bh-red hover:underline font-medium">
                    Sign in
                </a>
            </p>
        </form>
    </div>
</div>

<script>
function themeManager() {
    return {
        dark: false,
        init() {
            const saved = localStorage.getItem('bh-theme');
            if (saved) { this.dark = saved === 'dark'; }
            else { this.dark = window.matchMedia('(prefers-color-scheme: dark)').matches; }
        },
        toggleTheme() {
            this.dark = !this.dark;
            localStorage.setItem('bh-theme', this.dark ? 'dark' : 'light');
        }
    }
}
</script>
</body>
</html>