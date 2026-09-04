<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mini Invoicer')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-[#f8f9fb] text-gray-900 antialiased">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-gray-900 text-white grid place-items-center"><i class="ri-receipt-line"></i></span>
                    <span class="font-bold text-lg tracking-tight">Mini Invoicer</span>
                    <span class="hidden sm:inline text-xs bg-gray-100 border border-gray-200 rounded-full px-2 py-0.5">Laravel 13</span>
                </a>
                <div class="hidden md:flex items-center gap-1 text-sm">
                    <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
                    <a href="{{ route('invoices.index') }}" class="px-3 py-1.5 rounded-md {{ request()->routeIs('invoices.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Invoices</a>
                    <a href="{{ route('clients.index') }}" class="px-3 py-1.5 rounded-md {{ request()->routeIs('clients.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Clients</a>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.create') }}" class="hidden sm:inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg"><i class="ri-add-line"></i> New Invoice</a>
                <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 bg-white hover:bg-gray-50"><i class="ri-user-add-line text-gray-700"></i></a>
            </div>
        </div>
        <div class="md:hidden border-t border-gray-100 flex gap-1 px-4 py-2 text-sm">
            <a href="{{ route('dashboard') }}" class="flex-1 text-center py-1.5 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'bg-gray-50' }}">Dashboard</a>
            <a href="{{ route('invoices.index') }}" class="flex-1 text-center py-1.5 rounded-md {{ request()->routeIs('invoices.*') ? 'bg-gray-900 text-white' : 'bg-gray-50' }}">Invoices</a>
            <a href="{{ route('clients.index') }}" class="flex-1 text-center py-1.5 rounded-md {{ request()->routeIs('clients.*') ? 'bg-gray-900 text-white' : 'bg-gray-50' }}">Clients</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        @if (session('status'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 flex items-center gap-2 text-sm">
                <i class="ri-checkbox-circle-line text-emerald-600"></i> {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="max-w-6xl mx-auto px-6 py-8 text-center text-xs text-gray-400">
        Mini Invoicer · SQLite · No auth · <span class="mono">php artisan serve</span>
    </footer>
</body>
</html>
