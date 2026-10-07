<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-paper text-ink font-sans">
    <header class="border-b border-line bg-paper/95 backdrop-blur sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('products.index') }}" class="font-display text-xl font-bold tracking-tight">
                Toko<span class="text-accent">.</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('products.index') }}" class="hover:text-primary">Katalog</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="hover:text-primary">Pesanan Saya</a>
                    <a href="{{ route('addresses.index') }}" class="hover:text-primary">Alamat</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.products.index') }}" class="hover:text-primary">Admin</a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-4">
                <livewire:cart-counter />
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-muted hover:text-ink">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-muted hover:text-ink">Masuk</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>