<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kelola Produk</h1>
        <button wire:click="openCreate" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Produk Baru
        </button>
    </div>

    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="border-b text-left">
                <th class="p-3">Gambar</th>
                <th class="p-3">Nama</th>
                <th class="p-3">Kategori</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Stok</th>
                <th class="p-3">Status</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="border-b" wire:key="product-{{ $product->id }}">
                    <td class="p-3">
                        @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
                        @if ($primary)
                            <img src="{{ Storage::url($primary->path) }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded"></div>
                        @endif
                    </td>
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">{{ $product->category->name }}</td>
                    <td class="p-3">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="p-3">{{ $product->stock }}</td>
                    <td class="p-3">
                        <span class="{{ $product->is_active ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="p-3 space-x-2">
                        <button wire:click="openEdit({{ $product->id }})" class="text-blue-600">Edit</button>
                        <button
                            wire:click="delete({{ $product->id }})"
                            wire:confirm="Yakin hapus produk ini? Semua gambar juga akan terhapus."
                            class="text-red-600"
                        >
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $products->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
            <div class="bg-white p-6 rounded w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <h2 class="text-lg font-bold mb-4">
                    {{ $form->productModel ? 'Edit Produk' : 'Produk Baru' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm mb-1">Nama Produk</label>
                        <input type="text" wire:model="form.name" class="w-full border rounded p-2">
                        @error('form.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Kategori</label>
                        <select wire:model="form.category_id" class="w-full border rounded p-2">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('form.category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-1">Harga (Rp)</label>
                            <input type="number" wire:model="form.price" class="w-full border rounded p-2">
                            @error('form.price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Stok</label>
                            <input type="number" wire:model="form.stock" class="w-full border rounded p-2">
                            @error('form.stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Deskripsi</label>
                        <textarea wire:model="form.description" rows="3" class="w-full border rounded p-2"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="form.is_active">
                            <span class="text-sm">Aktif (tampil di katalog)</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Tambah Gambar (maks 5, 2MB per gambar)</label>
                        <input type="file" wire:model="form.newImages" multiple accept="image/*" class="w-full border rounded p-2">
                        @error('form.newImages.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="form.newImages" class="text-sm text-gray-500 mt-1">
                            Mengunggah...
                        </div>

                        @if ($form->newImages)
                            <div class="flex gap-2 mt-2">
                                @foreach ($form->newImages as $image)
                                    <img src="{{ $image->temporaryUrl() }}" class="w-16 h-16 object-cover rounded">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($form->productModel && $form->productModel->images->isNotEmpty())
                        <div>
                            <label class="block text-sm mb-2">Gambar Tersimpan</label>
                            <div class="flex gap-2 flex-wrap">
                                @foreach ($form->productModel->images as $image)
                                    <div class="relative" wire:key="img-{{ $image->id }}">
                                        <img src="{{ Storage::url($image->path) }}" class="w-16 h-16 object-cover rounded {{ $image->is_primary ? 'ring-2 ring-blue-600' : '' }}">
                                        <div class="flex gap-1 mt-1">
                                            <button type="button" wire:click="setPrimaryImage({{ $image->id }})" class="text-xs text-blue-600">Utama</button>
                                            <button type="button" wire:click="deleteImage({{ $image->id }})" wire:confirm="Hapus gambar ini?" class="text-xs text-red-600">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>