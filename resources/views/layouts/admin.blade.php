<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white text-ink font-sans">
    <div class="flex min-h-screen">
        <aside class="w-56 border-r border-line flex flex-col shrink-0">
            <div class="h-16 flex items-center px-5 border-b border-line">
                <span class="font-display font-bold text-lg">Admin</span>
            </div>
            <nav class="flex-1 py-4 text-sm">
                @foreach ([
                    ['label' => 'Produk', 'route' => 'admin.products.index'],
                    ['label' => 'Kategori', 'route' => 'admin.categories.index'],
                    ['label' => 'Pesanan', 'route' => 'admin.orders.index'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center px-5 py-2.5 {{ request()->routeIs($item['route']) ? 'bg-primary/5 text-primary border-r-2 border-primary font-medium' : 'text-muted hover:text-ink hover:bg-ink/[0.03]' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="p-4 border-t border-line">
                <a href="{{ route('products.index') }}" class="text-sm text-muted hover:text-ink">Lihat Toko</a>
            </div>
        </aside>

        <div class="flex-1 min-w-0">
            <header class="h-16 border-b border-line flex items-center justify-end px-6">
                <span class="text-sm text-muted">{{ auth()->user()->name }}</span>
            </header>
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>