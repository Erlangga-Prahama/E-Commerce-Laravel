<div class="pb-24 md:pb-0">
    {{-- ============ HEADER ============ --}}
    <div class="flex items-end justify-between gap-4 mb-5 md:mb-8">
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold tracking-tight">Kelola Produk</h1>
            @if (method_exists($products, 'total'))
                <p class="text-sm text-muted mt-1">{{ number_format($products->total(), 0, ',', '.') }} produk</p>
            @endif
        </div>

        <button wire:click="openCreate"
                class="btn-primary hidden md:inline-flex items-center gap-2 rounded-full px-5 py-2.5 shadow-sm transition active:scale-95">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Produk Baru
        </button>
    </div>

    {{-- FAB (mobile) --}}
    <button wire:click="openCreate" aria-label="Produk baru"
            class="md:hidden fixed right-4 bottom-[max(1.25rem,env(safe-area-inset-bottom))] z-30 flex h-14 items-center gap-2
                   rounded-full bg-primary px-5 text-white shadow-lg transition active:scale-95">
        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        <span class="text-sm font-medium">Produk Baru</span>
    </button>

    {{-- ============ LIST MOBILE (kartu) ============ --}}
    <div class="md:hidden flex flex-col gap-3">
        @forelse ($products as $product)
            @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            <div wire:key="m-product-{{ $product->id }}"
                 class="rounded-2xl bg-white p-3 ring-1 ring-line/60 shadow-sm">
                <div class="flex gap-3">
                    <div class="size-20 shrink-0 overflow-hidden rounded-xl bg-line/30">
                        @if ($primary)
                            <img src="{{ Storage::url($primary->path) }}" alt="{{ $product->name }}" loading="lazy"
                                 class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-muted/40">
                                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0021.75 19.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21zM13.5 8.25h.008v.008H13.5V8.25z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-sm font-medium leading-snug line-clamp-2">{{ $product->name }}</h3>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-medium
                                         {{ $product->is_active ? 'bg-success/10 text-success' : 'bg-line/60 text-muted' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-muted">{{ $product->category->name }}</p>
                        <div class="mt-2 flex items-center justify-between gap-2">
                            <p class="font-display font-bold text-primary">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            @if ($product->stock <= 0)
                                <span class="rounded-full bg-danger/10 px-2 py-0.5 text-[11px] font-medium text-danger">Habis</span>
                            @elseif ($product->stock <= 5)
                                <span class="rounded-full bg-danger/10 px-2 py-0.5 text-[11px] font-medium text-danger">Sisa {{ $product->stock }}</span>
                            @else
                                <span class="text-xs text-muted">Stok {{ $product->stock }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-2 gap-2 border-t border-line/60 pt-3">
                    <button wire:click="openEdit({{ $product->id }})"
                            class="h-10 rounded-full border border-line text-sm font-medium transition hover:border-primary hover:text-primary active:scale-[0.98]">
                        Edit
                    </button>
                    <button wire:click="delete({{ $product->id }})"
                            wire:confirm="Yakin hapus produk ini? Semua gambar juga akan terhapus."
                            class="h-10 rounded-full border border-danger/30 text-sm font-medium text-danger transition hover:bg-danger/5 active:scale-[0.98]">
                        Hapus
                    </button>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center py-16 text-center">
                <div class="mb-4 flex size-16 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-lg">Belum ada produk</h3>
                <p class="mt-1 text-sm text-muted">Ketuk "Produk Baru" untuk menambahkan produk pertama.</p>
            </div>
        @endforelse
    </div>

    {{-- ============ TABEL DESKTOP ============ --}}
    <div class="hidden md:block overflow-hidden rounded-2xl bg-white ring-1 ring-line/60 shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-line/20 text-left text-xs uppercase tracking-wider text-muted">
                    <th class="px-4 py-3 font-medium">Gambar</th>
                    <th class="px-4 py-3 font-medium">Nama</th>
                    <th class="px-4 py-3 font-medium">Kategori</th>
                    <th class="px-4 py-3 font-medium">Harga</th>
                    <th class="px-4 py-3 font-medium">Stok</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line/60">
                @forelse ($products as $product)
                    @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
                    <tr class="transition hover:bg-line/10" wire:key="product-{{ $product->id }}">
                        <td class="px-4 py-3">
                            <div class="size-12 overflow-hidden rounded-lg bg-line/30">
                                @if ($primary)
                                    <img src="{{ Storage::url($primary->path) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-muted">{{ $product->category->name }}</td>
                        <td class="px-4 py-3 tabular-nums">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 tabular-nums">
                            @if ($product->stock <= 5)
                                <span class="rounded-full bg-danger/10 px-2 py-0.5 text-xs font-medium text-danger">{{ $product->stock }}</span>
                            @else
                                {{ $product->stock }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                         {{ $product->is_active ? 'bg-success/10 text-success' : 'bg-line/60 text-muted' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                <button wire:click="openEdit({{ $product->id }})"
                                        class="rounded-full px-3 py-1.5 text-primary transition hover:bg-primary/10">Edit</button>
                                <button wire:click="delete({{ $product->id }})"
                                        wire:confirm="Yakin hapus produk ini? Semua gambar juga akan terhapus."
                                        class="rounded-full px-3 py-1.5 text-danger transition hover:bg-danger/10">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>

    {{-- ============ MODAL / BOTTOM SHEET ============ --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 backdrop-blur-[2px] md:items-center md:p-6"
             wire:click.self="$set('showModal', false)"
             @keydown.escape.window="$wire.set('showModal', false)"
             x-data="{
                 init() { document.body.classList.add('overflow-hidden'); },
                 destroy() { document.body.classList.remove('overflow-hidden'); }
             }">
            <div class="flex max-h-[94dvh] w-full max-w-2xl flex-col overflow-hidden rounded-t-3xl bg-white shadow-2xl md:max-h-[90dvh] md:rounded-2xl"
                 x-data="{ show: false }" x-init="$nextTick(() => show = true)" x-show="show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="translate-y-full opacity-0 md:translate-y-4"
                 x-transition:enter-end="translate-y-0 opacity-100">

                {{-- Header sheet --}}
                <div class="shrink-0 border-b border-line/60 px-5 pb-3 pt-3">
                    <div class="mx-auto mb-3 h-1 w-10 rounded-full bg-line md:hidden" aria-hidden="true"></div>
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-lg font-bold">
                            {{ $form->productModel ? 'Edit Produk' : 'Produk Baru' }}
                        </h2>
                        <button type="button" wire:click="$set('showModal', false)" aria-label="Tutup"
                                class="-mr-2 flex size-10 items-center justify-center rounded-full transition active:bg-line/40">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="save" class="flex min-h-0 flex-1 flex-col">
                    {{-- Body --}}
                    <div class="flex-1 space-y-5 overflow-y-auto px-5 py-5">
                        <div>
                            <label for="p-name" class="mb-1.5 block text-sm font-medium">Nama Produk</label>
                            <input id="p-name" type="text" wire:model="form.name" placeholder="Contoh: Kemeja Linen Putih"
                                   class="input w-full rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                                          @error('form.name') border-danger @enderror">
                            @error('form.name') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="p-cat" class="mb-1.5 block text-sm font-medium">Kategori</label>
                            <select id="p-cat" wire:model="form.category_id"
                                    class="input w-full rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                                           @error('form.category_id') border-danger @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('form.category_id') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="p-price" class="mb-1.5 block text-sm font-medium">Harga</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm text-muted">Rp</span>
                                    <input id="p-price" type="number" inputmode="numeric" min="0" wire:model="form.price" placeholder="0"
                                           class="input w-full rounded-lg pl-10 text-base transition focus:ring-2 focus:ring-primary/30
                                                  @error('form.price') border-danger @enderror">
                                </div>
                                @error('form.price') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="p-stock" class="mb-1.5 block text-sm font-medium">Stok</label>
                                <input id="p-stock" type="number" inputmode="numeric" min="0" wire:model="form.stock" placeholder="0"
                                       class="input w-full rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                                              @error('form.stock') border-danger @enderror">
                                @error('form.stock') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="p-desc" class="mb-1.5 block text-sm font-medium">Deskripsi</label>
                            <textarea id="p-desc" wire:model="form.description" rows="4" placeholder="Jelaskan bahan, ukuran, atau keunggulan produk"
                                      class="input w-full rounded-lg text-base transition focus:ring-2 focus:ring-primary/30"></textarea>
                            @error('form.description') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Toggle aktif --}}
                        <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-line p-3.5">
                            <span>
                                <span class="block text-sm font-medium">Aktif</span>
                                <span class="block text-xs text-muted">Tampil di katalog</span>
                            </span>
                            <span class="relative inline-flex shrink-0">
                                <input type="checkbox" wire:model="form.is_active" class="peer sr-only">
                                <span class="h-6 w-11 rounded-full bg-line transition peer-checked:bg-primary
                                             after:absolute after:left-0.5 after:top-0.5 after:size-5 after:rounded-full after:bg-white
                                             after:shadow after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                            </span>
                        </label>

                        {{-- ============ GAMBAR ============ --}}
                        <div class="space-y-3">
                            <p class="text-sm font-medium">Gambar Produk</p>

                            {{-- Panduan ukuran --}}
                            <div class="rounded-xl bg-primary/5 p-4 text-sm ring-1 ring-primary/15">
                                <div class="mb-2 flex items-center gap-2 font-medium text-primary">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                                    </svg>
                                    Ukuran gambar yang ideal
                                </div>
                                <ul class="space-y-1.5 text-ink/80">
                                    <li class="flex gap-2"><span class="text-primary">•</span><span><strong class="font-medium">Rasio 1:1 (persegi)</strong>, ideal <strong class="font-medium">1000 × 1000 px</strong>, minimal 800 × 800 px.</span></li>
                                    <li class="flex gap-2"><span class="text-primary">•</span><span>Format JPG, PNG, atau WebP. Maks 2 MB per gambar dan maks 5 gambar.</span></li>
                                    <li class="flex gap-2"><span class="text-primary">•</span><span>Letakkan produk di tengah dengan ruang kosong di sekelilingnya. Di katalog desktop gambar dipotong ke rasio 4:3, jadi bagian atas dan bawah bisa terpotong.</span></li>
                                    <li class="flex gap-2"><span class="text-primary">•</span><span>Gambar bertanda <em>Utama</em> tampil pertama di katalog dan keranjang.</span></li>
                                </ul>
                            </div>

                            {{-- Dropzone --}}
                            <label class="flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border-2 border-dashed border-line px-4 py-6
                                          text-center transition hover:border-primary hover:bg-primary/5 active:bg-primary/5">
                                <svg class="size-8 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                                <span class="text-sm font-medium">Ketuk untuk pilih gambar</span>
                                <span class="text-xs text-muted">Bisa pilih lebih dari satu</span>
                                <input type="file" wire:model="form.newImages" multiple accept="image/*" class="sr-only">
                            </label>

                            @error('form.newImages.*') <p class="text-sm text-danger">{{ $message }}</p> @enderror
                            @error('form.newImages') <p class="text-sm text-danger">{{ $message }}</p> @enderror

                            <div wire:loading wire:target="form.newImages" class="flex items-center gap-2 text-sm text-muted">
                                <svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Mengunggah...
                            </div>

                            {{-- Preview gambar baru --}}
                            @if ($form->newImages)
                                <div>
                                    <p class="mb-2 text-xs text-muted">Akan diunggah ({{ count($form->newImages) }})</p>
                                    <div class="grid grid-cols-4 gap-2 sm:grid-cols-5">
                                        @foreach ($form->newImages as $image)
                                            <div class="aspect-square overflow-hidden rounded-xl bg-line/30 ring-1 ring-line/60">
                                                <img src="{{ $image->temporaryUrl() }}" alt="" class="h-full w-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Gambar tersimpan --}}
                            @if ($form->productModel && $form->productModel->images->isNotEmpty())
                                <div>
                                    <p class="mb-2 text-xs text-muted">Gambar tersimpan ({{ $form->productModel->images->count() }})</p>
                                    <div class="grid grid-cols-3 gap-3 sm:grid-cols-4">
                                        @foreach ($form->productModel->images as $image)
                                            <div wire:key="img-{{ $image->id }}" class="flex flex-col gap-1.5">
                                                <div class="relative aspect-square overflow-hidden rounded-xl bg-line/30
                                                            {{ $image->is_primary ? 'ring-2 ring-primary' : 'ring-1 ring-line/60' }}">
                                                    <img src="{{ Storage::url($image->path) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                                    @if ($image->is_primary)
                                                        <span class="absolute left-1.5 top-1.5 rounded-full bg-primary px-2 py-0.5 text-[10px] font-medium text-white">Utama</span>
                                                    @endif
                                                </div>
                                                <div class="grid grid-cols-2 gap-1.5">
                                                    @if ($image->is_primary)
                                                        <span class="col-span-1"></span>
                                                    @else
                                                        <button type="button" wire:click="setPrimaryImage({{ $image->id }})"
                                                                class="h-9 rounded-full border border-line text-xs font-medium text-primary transition hover:border-primary active:scale-95">
                                                            Utama
                                                        </button>
                                                    @endif
                                                    <button type="button" wire:click="deleteImage({{ $image->id }})" wire:confirm="Hapus gambar ini?"
                                                            class="h-9 rounded-full border border-danger/30 text-xs font-medium text-danger transition hover:bg-danger/5 active:scale-95">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer sticky --}}
                    <div class="shrink-0 border-t border-line/60 bg-white px-5 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]">
                        <div class="grid grid-cols-[1fr_2fr] gap-3 md:flex md:justify-end">
                            <button type="button" wire:click="$set('showModal', false)"
                                    class="h-12 rounded-full border border-line text-sm font-medium transition hover:bg-line/30 active:scale-[0.98] md:px-6">
                                Batal
                            </button>
                            <button type="submit"
                                    class="btn-primary inline-flex h-12 items-center justify-center gap-2 rounded-full shadow-sm transition
                                           active:scale-[0.98] disabled:opacity-70 md:px-8"
                                    wire:loading.attr="disabled" wire:target="save,form.newImages">
                                <svg wire:loading wire:target="save" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="save">Simpan</span>
                                <span wire:loading wire:target="save">Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>