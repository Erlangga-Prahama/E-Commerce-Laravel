@php
    $links = [
        [
            'label' => 'Katalog',
            'url' => route('products.index'),
            'active' => request()->routeIs('products.*'),
            'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
        ],
    ];

    if (auth()->check()) {
        $links[] = [
            'label' => 'Pesanan Saya',
            'url' => route('orders.index'),
            'active' => request()->routeIs('orders.*'),
            'icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
        ];
        $links[] = [
            'label' => 'Alamat',
            'url' => route('addresses.index'),
            'active' => request()->routeIs('addresses.*'),
            'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z',
        ];
        if (auth()->user()->isAdmin()) {
            $links[] = [
                'label' => 'Admin',
                'url' => route('admin.products.index'),
                'active' => request()->routeIs('admin.*'),
                'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
            ];
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ config('app.name') }}</title>
    <style>[x-cloak]{display:none !important}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-dvh flex flex-col bg-paper text-ink font-sans antialiased">

<div x-data="{ menu: false }"
     x-effect="document.body.classList.toggle('overflow-hidden', menu)"
     @keydown.escape.window="menu = false"
     x-on:livewire:navigated.window="menu = false">

    {{-- ============ HEADER ============ --}}
    <header class="border-b border-line/70 bg-paper/90 backdrop-blur sticky top-0 z-40 pt-[env(safe-area-inset-top)]">
        <div class="max-w-6xl mx-auto px-4 h-14 md:h-16 flex items-center justify-between gap-3">
            <div class="flex items-center gap-1">
                {{-- Hamburger (mobile) --}}
                <button type="button" @click="menu = true" aria-label="Buka menu"
                        class="md:hidden -ml-2 flex size-11 items-center justify-center rounded-full active:bg-line/40 transition">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                <a href="{{ route('products.index') }}" wire:navigate class="font-display text-xl font-bold tracking-tight">
                    Toko<span class="text-accent">.</span>
                </a>
            </div>

            {{-- Nav desktop --}}
            <nav class="hidden md:flex items-center gap-1 text-sm">
                @foreach ($links as $link)
                    <a href="{{ $link['url'] }}" wire:navigate
                       class="rounded-full px-4 py-2 transition
                              {{ $link['active'] ? 'bg-primary/10 text-primary font-medium' : 'text-ink/80 hover:bg-line/40 hover:text-primary' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                <livewire:cart-counter />

                @auth
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button class="rounded-full px-4 py-2 text-sm text-muted transition hover:bg-line/40 hover:text-ink">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" wire:navigate
                       class="rounded-full bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm transition active:scale-95 hover:opacity-90">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ============ DRAWER MENU (mobile) ============ --}}
    <div class="md:hidden" x-cloak>
        {{-- Backdrop --}}
        <div x-show="menu" x-transition.opacity.duration.200ms @click="menu = false"
             class="fixed inset-0 z-50 bg-black/45 backdrop-blur-[2px]" aria-hidden="true"></div>

        {{-- Panel --}}
        <aside x-show="menu"
               x-transition:enter="transition ease-out duration-250"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col bg-paper shadow-2xl
                      pt-[env(safe-area-inset-top)] pb-[env(safe-area-inset-bottom)]"
               role="dialog" aria-modal="true" aria-label="Menu">
            <div class="flex h-14 items-center justify-between px-4 border-b border-line/70">
                <span class="font-display text-xl font-bold tracking-tight">Toko<span class="text-accent">.</span></span>
                <button type="button" @click="menu = false" aria-label="Tutup menu"
                        class="-mr-2 flex size-11 items-center justify-center rounded-full active:bg-line/40">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @auth
                <div class="px-4 py-4 border-b border-line/70">
                    <p class="text-xs text-muted">Masuk sebagai</p>
                    <p class="font-medium truncate">{{ auth()->user()->name }}</p>
                </div>
            @endauth

            <nav class="flex-1 overflow-y-auto p-3 flex flex-col gap-1">
                @foreach ($links as $link)
                    <a href="{{ $link['url'] }}" wire:navigate @click="menu = false"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-[15px] transition active:scale-[0.98]
                              {{ $link['active'] ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-line/40' }}">
                        <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="p-3 border-t border-line/70">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-[15px] text-danger transition hover:bg-danger/5 active:scale-[0.98]">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('login') }}" wire:navigate
                           class="btn-primary h-11 rounded-full inline-flex items-center justify-center">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate
                               class="h-11 rounded-full border border-line inline-flex items-center justify-center text-sm font-medium
                                      transition hover:border-primary hover:text-primary">Daftar</a>
                        @endif
                    </div>
                @endauth
            </div>
        </aside>
    </div>
</div>

{{-- ============ KONTEN ============ --}}
<main class="flex-1 w-full max-w-6xl mx-auto px-4 py-6 md:py-10">
    {{ $slot }}
</main>

{{-- ============ FOOTER ============ --}}
<footer class="border-t border-line/70 mt-10">
    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-sm text-muted">
        <a href="{{ route('products.index') }}" wire:navigate class="font-display font-bold text-ink">
            Toko<span class="text-accent">.</span>
        </a>
        <p>Ongkos kirim flat Rp15.000</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</footer>

@livewireScripts
</body>
</html>