<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Alamat Saya</h2>
        <button wire:click="openCreate" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded">+ Tambah Alamat</button>
    </div>

    @error('delete') <div class="bg-red-100 text-red-700 p-2 rounded mb-3 text-sm">{{ $message }}</div> @enderror

    <div class="space-y-3">
        @forelse ($addresses as $address)
            <div class="border rounded p-3" wire:key="address-{{ $address->id }}">
                <div class="flex justify-between">
                    <div>
                        <p class="font-medium">
                            {{ $address->recipient_name }}
                            @if ($address->is_default)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Utama</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                        <p class="text-sm text-gray-600">{{ $address->address_line }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    </div>
                    <div class="space-x-2 text-sm">
                        <button wire:click="openEdit({{ $address->id }})" class="text-blue-600">Edit</button>
                        <button wire:click="delete({{ $address->id }})" wire:confirm="Hapus alamat ini?" class="text-red-600">Hapus</button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Belum ada alamat tersimpan.</p>
        @endforelse
    </div>

    @if ($showForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4" wire:click.self="$set('showForm', false)">
            <div class="bg-white p-6 rounded w-full max-w-md">
                <h3 class="font-bold mb-4">{{ $editingId ? 'Edit Alamat' : 'Alamat Baru' }}</h3>
                <form wire:submit="save" class="space-y-3">
                    <input type="text" wire:model="recipient_name" placeholder="Nama Penerima" class="w-full border rounded p-2">
                    @error('recipient_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                    <input type="text" wire:model="phone" placeholder="No. Telepon" class="w-full border rounded p-2">
                    @error('phone') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                    <textarea wire:model="address_line" placeholder="Alamat Lengkap" rows="2" class="w-full border rounded p-2"></textarea>
                    @error('address_line') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" wire:model="city" placeholder="Kota" class="border rounded p-2">
                        <input type="text" wire:model="province" placeholder="Provinsi" class="border rounded p-2">
                    </div>
                    <input type="text" wire:model="postal_code" placeholder="Kode Pos" class="w-full border rounded p-2">

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="is_default"> Jadikan alamat utama
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 border rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>