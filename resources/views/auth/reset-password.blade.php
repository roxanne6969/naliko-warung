<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Naliko Warung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#3E2F1E] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="bg-[#F0E0CC] rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header Band --}}
            <div class="bg-[#5C4A35] px-8 py-7 text-center">
                <div class="w-16 h-16 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    @svg('heroicon-o-lock-open', 'w-8 h-8 text-[#F7E6CC]')
                </div>
                <h1 class="text-2xl font-bold text-[#F7E6CC]">Reset Password</h1>
                <p class="text-[#dbc7a9] text-sm mt-1">Buat password baru yang kuat dan mudah diingat.</p>
            </div>

            <div class="px-8 py-7">

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    {{-- Password Reset Token --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-envelope', 'w-4 h-4 text-[#9e8065]') Alamat Email
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $request->email) }}"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] placeholder-[#c4a882] transition"
                            required autofocus autocomplete="username" />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-lock-closed', 'w-4 h-4 text-[#9e8065]') Password Baru
                        </label>
                        <input type="password" id="password" name="password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] transition"
                            required autocomplete="new-password" />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-shield-check', 'w-4 h-4 text-[#9e8065]') Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password baru"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] transition"
                            required autocomplete="new-password" />
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end pt-1">
                        <button type="submit"
                            class="flex items-center gap-2 bg-[#5C4A35] hover:bg-[#3E2F1E] text-[#F7E6CC] font-semibold py-3 px-7 rounded-xl transition">
                            @svg('heroicon-o-check', 'w-4 h-4') Simpan Password
                        </button>
                    </div>
                </form>

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
