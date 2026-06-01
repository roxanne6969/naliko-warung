<x-guest-layout>
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-stone-800 to-stone-900">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-[#E8D5C1] rounded-3xl shadow-2xl p-8 space-y-8">
            <!-- Logo Placeholder -->
            <div class="flex justify-center">
                <div class="flex items-center justify-center w-24 h-24 bg-gray-300 rounded-full">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-stone-900">NALIKO</h1>
                <p class="text-sm text-stone-700 mt-1">Member Login</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-stone-800 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="test@example.com"
                        class="w-full px-4 py-3 bg-white border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-600 transition"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-stone-800 mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 bg-white border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-600 transition"
                        required
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                        type="checkbox"
                        id="remember_me"
                        name="remember"
                        class="w-4 h-4 rounded border-stone-300 text-stone-600 shadow-sm focus:ring-stone-500"
                    />
                    <span class="text-sm text-stone-700">{{ __('Remember me') }}</span>
                </label>

                <!-- Forgot Password + Login Button -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm text-stone-600 underline hover:text-stone-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-500"
                        >
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button
                        type="submit"
                        class="bg-stone-700 hover:bg-stone-800 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-xs text-stone-600">
                    © 2026 Naliko Warung. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
