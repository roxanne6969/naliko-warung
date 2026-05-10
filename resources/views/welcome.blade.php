<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naliko Warung - Welcome</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white">
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-stone-900 mb-4">NALIKO WARUNG</h1>
            <p class="text-lg text-stone-600 mb-8">Sistem Manajemen Warung</p>
            
            <div class="flex gap-4 justify-center">
                <a href="{{ route('admin.login') }}" class="px-6 py-3 bg-stone-700 text-white rounded-lg hover:bg-stone-800 transition">
                    Admin Login
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 bg-stone-300 text-stone-900 rounded-lg hover:bg-stone-400 transition">
                    User Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
