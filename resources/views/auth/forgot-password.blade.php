<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Naliko Warung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#3E2F1E] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="bg-[#F0E0CC] rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header Band --}}
            <div class="bg-[#5C4A35] px-8 py-7 text-center">
                <div class="w-16 h-16 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    @svg('heroicon-o-key', 'w-8 h-8 text-[#F7E6CC]')
                </div>
                <h1 class="text-2xl font-bold text-[#F7E6CC]">Lupa Password?</h1>
                <p class="text-[#dbc7a9] text-sm mt-1">Kami akan kirimkan link reset ke email kamu.</p>
            </div>

            <div class="px-8 py-7">

                {{-- Description --}}
                <div class="bg-[#fdf5ec] border border-[#e8d5c1] rounded-xl p-4 mb-5 flex gap-3">
                    @svg('heroicon-o-information-circle', 'w-5 h-5 text-[#5C4A35] flex-shrink-0 mt-0.5')
                    <p class="text-sm text-[#5C4A35]">
                        Masukkan alamat email akunmu dan kami akan mengirimkan link untuk mereset password.
                    </p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                        @svg('heroicon-o-check-circle', 'w-4 h-4 text-green-600')
                        <p class="text-sm text-green-700 font-semibold">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#3E2F1E] mb-1.5 flex items-center gap-1">
                            @svg('heroicon-o-envelope', 'w-4 h-4 text-[#9e8065]') Alamat Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 bg-white border border-[#e8d5c1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-[#3E2F1E] placeholder-[#c4a882] transition"
                            required autofocus />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-1 text-sm text-[#9e8065] hover:text-[#5C4A35] hover:underline transition">
                            @svg('heroicon-o-arrow-left', 'w-3.5 h-3.5') Kembali Login
                        </a>
                        <button type="submit"
                            class="flex items-center gap-2 bg-[#5C4A35] hover:bg-[#3E2F1E] text-[#F7E6CC] font-semibold py-3 px-7 rounded-xl transition">
                            @svg('heroicon-o-paper-airplane', 'w-4 h-4') Kirim Link
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
