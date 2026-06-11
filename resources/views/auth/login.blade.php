<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Naliko Warung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#3E2F1E] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-[#F0E0CC] rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header Band --}}
            <div class="bg-[#5C4A35] px-8 py-7 text-center">
                <div class="w-16 h-16 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    @svg('heroicon-o-building-storefront', 'w-8 h-8 text-[#F7E6CC]')
                </div>
                <h1 class="text-2xl font-bold text-[#F7E6CC]">Naliko Warung</h1>
                <p class="text-[#dbc7a9] text-sm mt-1">Selamat datang! Silakan masuk untuk melanjutkan.</p>
            </div>

            <div class="px-8 py-7">

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                        @svg('heroicon-o-check-circle', 'w-4 h-4 text-green-600')
                        <p class="text-sm text-green-700 font-semibold">{{ session('status') }}</p>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-envelope', 'w-4 h-4 text-[#9e8065]') Alamat Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] placeholder-[#c4a882] transition"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-lock-closed', 'w-4 h-4 text-[#9e8065]') Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] transition"
                            required
                            autocomplete="current-password"
                        />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            id="remember_me"
                            name="remember"
                            class="w-4 h-4 rounded border-[#e8d5c1] text-[#5C4A35] focus:ring-[#5C4A35]"
                        />
                        <span class="text-sm text-[#9e8065]">Ingat saya</span>
                    </label>

                    {{-- Forgot Password + Login Button --}}
                    <div class="flex items-center justify-between pt-1">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-[#9e8065] hover:text-[#5C4A35] hover:underline transition">
                                Lupa password?
                            </a>
                        @endif

                        <button
                            type="submit"
                            class="flex items-center gap-2 bg-[#5C4A35] hover:bg-[#3E2F1E] text-[#F7E6CC] font-semibold py-3 px-7 rounded-xl transition">
                            @svg('heroicon-o-arrow-right-on-rectangle', 'w-4 h-4') Masuk
                        </button>
                    </div>
                </form>

                {{-- Footer --}}
                <div class="text-center mt-6 pt-6 border-t border-[#e8d5c1]">
                    <p class="text-xs text-[#9e8065]">
                        &copy; {{ date('Y') }} Naliko Warung. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
