<div>
    {{-- Toolbar: search + kategori (sticky di mobile) --}}
    <div class="sticky top-0 z-20 -mx-4 px-4 pt-3 pb-3 mb-5 bg-white/85 backdrop-blur border-b border-line/60
                md:static md:mx-0 md:px-0 md:pt-0 md:pb-0 md:mb-8 md:bg-transparent md:backdrop-blur-none md:border-0">
        {{-- Search --}}
        <div class="relative md:max-w-md">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input
                type="search"
                wire:model.live.debounce.400ms="search"
                placeholder="Cari produk..."
                enterkeyhint="search"
                class="input w-full pl-11 pr-11 py-3 rounded-full text-base md:text-sm"
            >
            @if ($search)
                <button type="button" wire:click="$set('search', '')" aria-label="Hapus pencarian"
                        class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-muted hover:text-primary">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Kategori: scroll horizontal di mobile, wrap di desktop --}}
        <div class="mt-3 -mx-4 px-4 flex gap-2 overflow-x-auto snap-x
                    [scrollbar-width:none] [&::-webkit-scrollbar]:hidden
                    md:mx-0 md:px-0 md:flex-wrap md:overflow-visible">
            <button wire:click="$set('categoryId', null)"
                    class="pill shrink-0 snap-start whitespace-nowrap {{ !$categoryId ? 'pill-active' : '' }}">
                Semua
            </button>
            @foreach ($categories as $category)
                <button wire:click="$set('categoryId', {{ $category->id }})" wire:key="cat-{{ $category->id }}"
                        class="pill shrink-0 snap-start whitespace-nowrap {{ (string) $categoryId === (string) $category->id ? 'pill-active' : '' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Indikator loading: progress bar tipis --}}
    <div wire:loading.delay class="fixed inset-x-0 top-0 z-50 h-0.5 overflow-hidden bg-primary/10" aria-hidden="true">
        <div class="h-full w-1/3 bg-primary animate-pulse"></div>
    </div>

    {{-- Jumlah hasil --}}
    @if (method_exists($products, 'total') && $products->total() > 0)
        <p class="text-xs text-muted mb-4">{{ number_format($products->total(), 0, ',', '.') }} produk</p>
    @endif

    {{-- Grid produk --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-3 gap-y-6 sm:gap-x-5 sm:gap-y-8 md:grid-cols-3 transition-opacity"
         wire:loading.class.delay="opacity-60">
        @forelse ($products as $product)
            @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            <a href="{{ route('products.show', $product->slug) }}" wire:key="product-{{ $product->id }}"
               class="group flex flex-col rounded-2xl bg-white ring-1 ring-line/60 overflow-hidden shadow-sm
                      transition hover:shadow-lg hover:-translate-y-0.5 active:scale-[0.98]">
                {{-- Gambar --}}
                <div class="relative aspect-square sm:aspect-[4/3] bg-line/30 overflow-hidden">
                    @if ($primary)
                        <img src="{{ Storage::url($primary->path) }}" alt="{{ $product->name }}" loading="lazy" decoding="async"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-muted/50">
                            <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0021.75 19.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21zM13.5 8.25h.008v.008H13.5V8.25z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Badge kategori melayang di atas gambar --}}
                    <span class="badge absolute left-2 top-2 bg-white/90 text-primary backdrop-blur text-[11px] shadow-sm">
                        {{ $product->category->name }}
                    </span>
                </div>

                {{-- Info --}}
                <div class="flex flex-1 flex-col p-3 sm:p-4">
                    <h3 class="text-sm leading-snug line-clamp-2 min-h-[2.5rem]">{{ $product->name }}</h3>
                    <div class="mt-auto pt-2 flex items-center justify-between gap-2">
                        <p class="font-display font-bold text-base sm:text-lg text-primary">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <span class="hidden sm:flex size-8 items-center justify-center rounded-full bg-primary/10 text-primary
                                     transition group-hover:bg-primary group-hover:text-white" aria-hidden="true">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full flex flex-col items-center text-center py-16 px-6">
                <div class="mb-4 flex size-16 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-lg">Produk tidak ditemukan</h3>
                <p class="text-sm text-muted mt-1 max-w-xs">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                @if ($search || $categoryId)
                    <button type="button" wire:click="$set('search', ''); $set('categoryId', null)"
                            class="mt-5 rounded-full border border-primary/30 px-5 py-2.5 text-sm font-medium text-primary
                                   transition hover:bg-primary/5 active:scale-95">
                        Reset filter
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-10">{{ $products->links() }}</div>
</div>