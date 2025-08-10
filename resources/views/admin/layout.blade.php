@php
    $siteTitle = \App\Models\Setting::first()->site_title ?? 'Admin';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteTitle }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
</head>
<body class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:block">
        <div class="p-6 text-xl font-bold border-b">{{ $siteTitle }}</div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }}">Trang chủ</a>
            <a href="{{ route('admin.settings.edit') }}" class="block py-2 px-3 rounded hover:bg-gray-200 {{ request()->routeIs('admin.settings.edit') ? 'bg-gray-200' : '' }}">Cài đặt</a>
            <a href="{{ route('admin.genres.index') }}" class="block py-2 px-3 rounded hover:bg-gray-200 {{ request()->routeIs('admin.genres.*') ? 'bg-gray-200' : '' }}">Thể loại</a>
            <a href="{{ route('admin.mangas.index') }}" class="block py-2 px-3 rounded hover:bg-gray-200 {{ request()->routeIs('admin.mangas.*') ? 'bg-gray-200' : '' }}">Truyện</a>
            <!-- Placeholder for menu editing -->
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
        <!-- Top Bar -->
        <header class="flex items-center justify-between bg-white shadow px-4 py-2">
            <h1 class="text-xl font-semibold">@yield('title', 'Admin')</h1>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Đăng xuất</button>
            </form>
        </header>
        <main class="p-6 flex-1">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-2 mb-4 rounded">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
