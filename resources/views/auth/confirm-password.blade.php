<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password - Naliko Warung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#3E2F1E] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="bg-[#F0E0CC] rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header Band --}}
            <div class="bg-[#5C4A35] px-8 py-7 text-center">
                <div class="w-16 h-16 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    @svg('heroicon-o-shield-check', 'w-8 h-8 text-[#F7E6CC]')
                </div>
                <h1 class="text-2xl font-bold text-[#F7E6CC]">Konfirmasi Keamanan</h1>
                <p class="text-[#dbc7a9] text-sm mt-1">Area ini memerlukan verifikasi password.</p>
            </div>

            <div class="px-8 py-7">

                {{-- Description --}}
                <div class="bg-[#fdf5ec] border border-[#e8d5c1] rounded-xl p-4 mb-5 flex gap-3">
                    @svg('heroicon-o-shield-exclamation', 'w-5 h-5 text-[#5C4A35] flex-shrink-0 mt-0.5')
                    <p class="text-sm text-[#5C4A35]">
                        Ini adalah area aman. Harap konfirmasi password sebelum melanjutkan.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-lock-closed', 'w-4 h-4 text-[#9e8065]') Password
                        </label>
                        <input type="password" id="password" name="password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] transition"
                            required autocomplete="current-password" />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-1">
                        <button type="submit"
                            class="flex items-center gap-2 bg-[#5C4A35] hover:bg-[#3E2F1E] text-[#F7E6CC] font-semibold py-3 px-7 rounded-xl transition">
                            @svg('heroicon-o-check-circle', 'w-4 h-4') Konfirmasi
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
