@php
    $totalQty = $cart->items->sum('quantity');
@endphp

<div class="max-w-4xl mx-auto pb-36 md:pb-0">
    {{-- Header --}}
    <div class="flex items-end justify-between gap-4 mb-5 md:mb-8">
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold tracking-tight">Keranjang</h1>
            @if ($cart->items->isNotEmpty())
                <p class="text-sm text-muted mt-1">{{ $totalQty }} barang</p>
            @endif
        </div>
        @if ($cart->items->isNotEmpty())
            <a href="{{ route('products.index') }}" wire:navigate
               class="text-sm text-primary hover:underline underline-offset-4 whitespace-nowrap">
                + Tambah barang
            </a>
        @endif
    </div>

    @if ($cart->items->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center text-center py-16 px-6">
            <div class="mb-5 flex size-20 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
            </div>
            <h2 class="font-display font-bold text-lg">Keranjang kamu kosong</h2>
            <p class="text-sm text-muted mt-1 max-w-xs">Yuk, lihat katalog dan tambahkan produk yang kamu suka.</p>
            <a href="{{ route('products.index') }}" wire:navigate
               class="btn-primary mt-6 rounded-full px-6 py-3 inline-flex items-center gap-2 shadow-sm transition active:scale-95">
                Mulai belanja
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-[1fr_20rem] gap-6 md:gap-8 items-start">
            {{-- ============ DAFTAR ITEM ============ --}}
            <div class="flex flex-col gap-3 transition-opacity" wire:loading.class.delay="opacity-60"
                 wire:target="updateQuantity,removeItem">
                @foreach ($cart->items as $item)
                    @php
                        $img = $item->product->images->first();
                        $atMax = $item->quantity >= $item->product->stock;
                    @endphp
                    <div wire:key="cart-item-{{ $item->id }}"
                         class="flex gap-3 sm:gap-4 rounded-2xl bg-white p-3 sm:p-4 ring-1 ring-line/60 shadow-sm">
                        {{-- Gambar --}}
                        <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate
                           class="shrink-0 size-20 sm:size-24 rounded-xl bg-line/30 overflow-hidden">
                            @if ($img)
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $item->product->name }}"
                                     loading="lazy" decoding="async" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-muted/40">
                                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0021.75 19.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21zM13.5 8.25h.008v.008H13.5V8.25z"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 flex flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate
                                   class="text-sm sm:text-base leading-snug line-clamp-2 font-medium">
                                    {{ $item->product->name }}
                                </a>
                                <button type="button" wire:click="removeItem({{ $item->id }})"
                                        aria-label="Hapus {{ $item->product->name }}"
                                        class="-mr-1 -mt-1 flex size-9 shrink-0 items-center justify-center rounded-full text-muted
                                               transition hover:bg-danger/10 hover:text-danger active:scale-90">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>

                            <p class="text-xs text-muted mt-0.5">
                                Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                @if ($atMax)
                                    <span class="text-danger"> · stok maksimal</span>
                                @endif
                            </p>

                            <div class="mt-auto pt-3 flex items-center justify-between gap-2">
                                {{-- Stepper --}}
                                <div class="flex items-center rounded-full ring-1 ring-line bg-white">
                                    <button type="button"
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            @disabled($item->quantity <= 1)
                                            aria-label="Kurangi jumlah"
                                            class="flex size-9 items-center justify-center rounded-full text-lg transition
                                                   active:bg-line/40 disabled:opacity-30 disabled:cursor-not-allowed">−</button>
                                    <span class="min-w-7 text-center text-sm font-medium tabular-nums" aria-live="polite">
                                        {{ $item->quantity }}
                                    </span>
                                    <button type="button"
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            @disabled($atMax)
                                            aria-label="Tambah jumlah"
                                            class="flex size-9 items-center justify-center rounded-full text-lg transition
                                                   active:bg-line/40 disabled:opacity-30 disabled:cursor-not-allowed">+</button>
                                </div>

                                {{-- Subtotal item --}}
                                <p class="font-display font-bold text-base sm:text-lg">
                                    Rp{{ number_format($item->subtotal(), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ============ RINGKASAN: bottom bar (mobile) / kartu sticky (desktop) ============ --}}
            <div class="fixed inset-x-0 bottom-0 z-30 border-t border-line/60 bg-white/95 backdrop-blur
                        px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]
                        md:sticky md:top-6 md:bottom-auto md:rounded-2xl md:border-0 md:bg-white md:p-5
                        md:ring-1 md:ring-line/60 md:shadow-sm">
                <h2 class="hidden md:block font-display font-bold text-lg mb-4">Ringkasan</h2>

                <div class="hidden md:flex items-center justify-between text-sm text-muted mb-2">
                    <span>{{ $totalQty }} barang</span>
                </div>

                <div class="flex items-center gap-4 md:flex-col md:items-stretch md:gap-4">
                    <div class="flex-1 md:flex md:items-center md:justify-between md:border-t md:border-line/60 md:pt-4">
                        <p class="text-xs text-muted md:text-sm md:font-medium md:text-ink">Subtotal</p>
                        <p class="font-display text-xl md:text-xl font-bold text-primary leading-tight">
                            Rp{{ number_format($cart->subtotal(), 0, ',', '.') }}
                        </p>
                    </div>

                    <a href="{{ route('checkout.index') }}" wire:navigate
                       class="btn-primary h-12 rounded-full px-6 inline-flex items-center justify-center gap-2 shadow-sm
                              transition active:scale-[0.98] md:w-full">
                        Checkout
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>