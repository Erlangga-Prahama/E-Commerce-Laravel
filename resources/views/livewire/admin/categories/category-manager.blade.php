<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-display text-2xl font-bold">Kategori</h1>
        <button wire:click="openCreate" class="btn-primary">Tambah Kategori</button>
    </div>

    @error('delete')
        <div class="border border-danger/30 bg-danger/5 text-danger text-sm px-4 py-3 rounded-sm mb-4">{{ $message }}</div>
    @enderror

    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-line text-left text-muted">
                <th class="py-3 font-medium">Nama</th>
                <th class="py-3 font-medium">Slug</th>
                <th class="py-3 font-medium">Produk</th>
                <th class="py-3 font-medium"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="border-b border-line" wire:key="category-{{ $category->id }}">
                    <td class="py-3">{{ $category->name }}</td>
                    <td class="py-3 text-muted">{{ $category->slug }}</td>
                    <td class="py-3">{{ $category->products_count }}</td>
                    <td class="py-3 text-right space-x-3">
                        <button wire:click="openEdit({{ $category->id }})" class="text-primary hover:underline">Ubah</button>
                        <button wire:click="delete({{ $category->id }})" wire:confirm="Yakin hapus kategori ini?" class="text-danger hover:underline">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6">{{ $categories->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 bg-ink/40 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
            <div class="bg-white border border-line p-6 rounded-sm w-full max-w-md">
                <h2 class="font-display font-bold text-lg mb-4">{{ $form->categoryModel ? 'Ubah Kategori' : 'Kategori Baru' }}</h2>
                <form wire:submit="save">
                    <label class="block text-sm text-muted mb-1">Nama Kategori</label>
                    <input type="text" wire:model="form.name" class="input mb-1">
                    @error('form.name') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>