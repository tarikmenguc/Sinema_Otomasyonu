<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Navbar --}}
    @include('admin.layouts.navbar')

    {{-- Sayfa içeriği --}}
    <div class="container mx-auto p-4">
        @yield('content')
    </div>
   @stack('scripts')
</body>
</html>
