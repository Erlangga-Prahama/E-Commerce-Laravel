<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kelola Kategori</h1>
        <button wire:click="openCreate" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Kategori Baru
        </button>
    </div>

    @error('delete')
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $message }}</div>
    @enderror

    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="border-b text-left">
                <th class="p-3">Nama</th>
                <th class="p-3">Slug</th>
                <th class="p-3">Jumlah Produk</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="border-b" wire:key="category-{{ $category->id }}">
                    <td class="p-3">{{ $category->name }}</td>
                    <td class="p-3 text-gray-500">{{ $category->slug }}</td>
                    <td class="p-3">{{ $category->products_count }}</td>
                    <td class="p-3 space-x-2">
                        <button wire:click="openEdit({{ $category->id }})" class="text-blue-600">Edit</button>
                        <button
                            wire:click="delete({{ $category->id }})"
                            wire:confirm="Yakin hapus kategori ini?"
                            class="text-red-600"
                        >
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $categories->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center" wire:click.self="$set('showModal', false)">
            <div class="bg-white p-6 rounded w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">
                    {{ $form->categoryModel ? 'Edit Kategori' : 'Kategori Baru' }}
                </h2>

                <form wire:submit="save">
                    <label class="block text-sm mb-1">Nama Kategori</label>
                    <input
                        type="text"
                        wire:model="form.name"
                        class="w-full border rounded p-2 mb-1"
                    >
                    @error('form.name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border rounded">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>