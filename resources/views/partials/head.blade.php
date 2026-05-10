<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Naliko Warung Admin Panel">
<title>{{ isset($title) ? $title . ' - ' : '' }}Naliko Warung</title>

<!-- Tailwind CSS -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
