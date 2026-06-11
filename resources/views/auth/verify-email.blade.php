<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Naliko Warung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#3E2F1E] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="bg-[#F0E0CC] rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header Band --}}
            <div class="bg-[#5C4A35] px-8 py-7 text-center">
                <div class="w-16 h-16 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    @svg('heroicon-o-envelope-open', 'w-8 h-8 text-[#F7E6CC]')
                </div>
                <h1 class="text-2xl font-bold text-[#F7E6CC]">Verifikasi Email</h1>
                <p class="text-[#dbc7a9] text-sm mt-1">Satu langkah lagi untuk mulai memesan!</p>
            </div>

            <div class="px-8 py-7">

                {{-- Description --}}
                <div class="bg-[#fdf5ec] border border-[#e8d5c1] rounded-xl p-4 mb-5 flex gap-3">
                    @svg('heroicon-o-information-circle', 'w-5 h-5 text-[#5C4A35] flex-shrink-0 mt-0.5')
                    <p class="text-sm text-[#5C4A35]">
                        Terima kasih sudah mendaftar! Sebelum memulai, mohon verifikasi alamat email kamu dengan mengklik link yang telah kami kirimkan.
                    </p>
                </div>

                {{-- Status --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                        @svg('heroicon-o-check-circle', 'w-4 h-4 text-green-600')
                        <p class="text-sm text-green-700 font-semibold">
                            Link verifikasi baru telah dikirim ke email kamu.
                        </p>
                    </div>
                @endif

                <div class="flex flex-col gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] hover:bg-[#3E2F1E] text-[#F7E6CC] font-semibold py-3 px-6 rounded-xl transition">
                            @svg('heroicon-o-paper-airplane', 'w-4 h-4') Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 border border-[#e8d5c1] text-[#9e8065] py-3 px-6 rounded-xl hover:bg-[#fdf5ec] hover:text-red-500 hover:border-red-200 transition text-sm">
                            @svg('heroicon-o-arrow-right-on-rectangle', 'w-4 h-4') Keluar
                        </button>
                    </form>
                </div>

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
