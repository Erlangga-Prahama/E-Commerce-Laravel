@php
    $images = $product->images->sortByDesc('is_primary')->values();
    $inStock = $product->stock > 0;
    $longDesc = mb_strlen($product->description ?? '') > 200;
@endphp

<div class="max-w-4xl mx-auto pb-32 md:pb-0">
    {{-- Back (desktop) --}}
    <a href="{{ route('products.index') }}" wire:navigate
       class="hidden md:inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink transition">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali ke katalog
    </a>

    <div class="grid md:grid-cols-2 gap-6 md:gap-10 md:mt-4">
        {{-- ============ GALERI ============ --}}
        <div x-data="{ active: 0 }" class="-mx-4 md:mx-0">
            <div class="relative">
                {{-- Track: swipe di mobile, klik thumbnail di desktop --}}
                <div x-ref="track"
                     @scroll.debounce.80ms="active = Math.round($refs.track.scrollLeft / $refs.track.clientWidth)"
                     class="flex overflow-x-auto snap-x snap-mandatory bg-line/30 md:rounded-2xl
                            [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    @forelse ($images as $image)
                        <div class="w-full shrink-0 snap-center aspect-square">
                            <img src="{{ Storage::url($image->path) }}" alt="{{ $product->name }} {{ $loop->iteration }}"
                                 @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                 decoding="async" class="w-full h-full object-cover">
                        </div>
                    @empty
                        <div class="w-full shrink-0 aspect-square flex items-center justify-center text-muted/40">
                            <svg class="size-16" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0021.75 19.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21zM13.5 8.25h.008v.008H13.5V8.25z"/>
                            </svg>
                        </div>
                    @endforelse
                </div>

                {{-- Tombol kembali melayang (mobile) --}}
                <a href="{{ route('products.index') }}" wire:navigate aria-label="Kembali ke katalog"
                   class="md:hidden absolute left-4 top-4 flex size-10 items-center justify-center rounded-full
                          bg-white/90 text-ink shadow-md backdrop-blur active:scale-95 transition">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                </a>

                {{-- Counter --}}
                @if ($images->count() > 1)
                    <span class="absolute right-4 bottom-4 rounded-full bg-black/55 px-2.5 py-1 text-xs font-medium text-white backdrop-blur">
                        <span x-text="active + 1"></span>/{{ $images->count() }}
                    </span>
                @endif
            </div>

            @if ($images->count() > 1)
                {{-- Dots (mobile) --}}
                <div class="flex justify-center gap-1.5 mt-3 md:hidden" aria-hidden="true">
                    @foreach ($images as $image)
                        <span class="h-1.5 rounded-full transition-all duration-300"
                              :class="active === {{ $loop->index }} ? 'w-5 bg-primary' : 'w-1.5 bg-line'"></span>
                    @endforeach
                </div>

                {{-- Thumbnails (desktop) --}}
                <div class="hidden md:flex gap-2 mt-3">
                    @foreach ($images as $image)
                        <button type="button"
                                @click="$refs.track.scrollTo({ left: {{ $loop->index }} * $refs.track.clientWidth, behavior: 'smooth' })"
                                class="size-16 rounded-lg overflow-hidden ring-2 transition"
                                :class="active === {{ $loop->index }} ? 'ring-primary' : 'ring-transparent opacity-70 hover:opacity-100'"
                                aria-label="Lihat gambar {{ $loop->iteration }}">
                            <img src="{{ Storage::url($image->path) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ============ INFO ============ --}}
        <div class="flex flex-col">
            <div class="flex items-center gap-2">
                <span class="badge bg-primary/5 text-primary">{{ $product->category->name }}</span>
                @if ($inStock && $product->stock <= 5)
                    <span class="badge bg-danger/10 text-danger">Sisa {{ $product->stock }}</span>
                @endif
            </div>

            <h1 class="font-display text-2xl md:text-3xl font-bold leading-tight mt-3">{{ $product->name }}</h1>

            <p class="font-display text-3xl font-bold text-primary mt-3">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </p>

            <p class="mt-3 inline-flex items-center gap-2 text-sm">
                @if ($inStock)
                    <span class="size-2 rounded-full bg-success"></span>
                    <span class="text-success">Stok tersedia ({{ $product->stock }})</span>
                @else
                    <span class="size-2 rounded-full bg-danger"></span>
                    <span class="text-danger">Stok habis</span>
                @endif
            </p>

            {{-- Deskripsi --}}
            <div class="mt-6 pt-6 border-t border-line/60" x-data="{ open: false }">
                <h2 class="font-display font-bold text-base mb-2">Deskripsi</h2>
                <p class="text-sm text-ink/80 leading-relaxed whitespace-pre-line"
                   :class="open ? '' : 'line-clamp-4 md:line-clamp-none'">{{ $product->description }}</p>
                @if ($longDesc)
                    <button type="button" @click="open = !open"
                            class="md:hidden mt-2 text-sm font-medium text-primary active:opacity-70"
                            x-text="open ? 'Tampilkan lebih sedikit' : 'Selengkapnya'"></button>
                @endif
            </div>

            {{-- Pesan keranjang (toast di mobile, banner di desktop) --}}
            @if (session('cart-message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity
                     class="fixed inset-x-4 bottom-28 z-40 md:static md:mt-5 flex items-center gap-2.5
                            border border-success/30 bg-white md:bg-success/5 text-success text-sm px-4 py-3 rounded-xl shadow-lg md:shadow-none"
                     role="status">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('cart-message') }}</span>
                </div>
            @endif

            {{-- ============ AKSI: bottom bar (mobile) / inline (desktop) ============ --}}
            @if ($inStock)
                <form wire:submit="addToCart"
                      class="fixed inset-x-0 bottom-0 z-30 border-t border-line/60 bg-white/95 backdrop-blur
                             px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]
                             md:static md:mt-6 md:border-0 md:bg-transparent md:backdrop-blur-none md:p-0">
                    @error('quantity')
                        <p class="text-danger text-sm mb-2">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center gap-3">
                        {{-- Stepper kuantitas --}}
                        <div class="flex items-center rounded-full ring-1 ring-line bg-white shrink-0">
                            <button type="button" aria-label="Kurangi"
                                    @click="$wire.quantity = Math.max(1, (parseInt($wire.quantity) || 1) - 1)"
                                    class="flex size-11 items-center justify-center rounded-full text-lg active:bg-line/40">−</button>
                            <input type="number" wire:model="quantity" min="1" max="{{ $product->stock }}" inputmode="numeric"
                                   aria-label="Jumlah"
                                   class="w-9 bg-transparent text-center text-base font-medium outline-none
                                          [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                            <button type="button" aria-label="Tambah"
                                    @click="$wire.quantity = Math.min({{ $product->stock }}, (parseInt($wire.quantity) || 1) + 1)"
                                    class="flex size-11 items-center justify-center rounded-full text-lg active:bg-line/40">+</button>
                        </div>

                        {{-- Tombol --}}
                        <button type="submit"
                                class="btn-primary flex-1 h-11 rounded-full inline-flex items-center justify-center gap-2
                                       shadow-sm transition active:scale-[0.98] disabled:opacity-70"
                                wire:loading.attr="disabled" wire:target="addToCart">
                            <svg wire:loading wire:target="addToCart" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="addToCart" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                            </svg>
                            <span wire:loading.remove wire:target="addToCart">Tambah ke Keranjang</span>
                            <span wire:loading wire:target="addToCart">Menambahkan...</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="fixed inset-x-0 bottom-0 z-30 border-t border-line/60 bg-white/95 backdrop-blur
                            px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]
                            md:static md:mt-6 md:border-0 md:bg-transparent md:p-0">
                    <button type="button" disabled
                            class="w-full h-11 rounded-full bg-line/50 text-muted text-sm font-medium cursor-not-allowed">
                        Stok habis
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>