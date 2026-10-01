<?php

namespace App\Livewire;

use App\Models\Address;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AddressManager extends Component
{
    public ?int $editingId = null;
    public string $recipient_name = '';
    public string $phone = '';
    public string $address_line = '';
    public string $city = '';
    public string $province = '';
    public string $postal_code = '';
    public bool $is_default = false;
    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'recipient_name', 'phone', 'address_line', 'city', 'province', 'postal_code', 'is_default']);
        $this->showForm = true;
    }

    public function openEdit(Address $address): void
    {
        $this->authorize('update', $address);
        $this->editingId = $address->id;
        $this->recipient_name = $address->recipient_name;
        $this->phone = $address->phone;
        $this->address_line = $address->address_line;
        $this->city = $address->city;
        $this->province = $address->province;
        $this->postal_code = $address->postal_code;
        $this->is_default = $address->is_default;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = $this->only(['recipient_name', 'phone', 'address_line', 'city', 'province', 'postal_code']);

        if ($this->is_default) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }
        $data['is_default'] = $this->is_default;

        if ($this->editingId) {
            $address = Address::findOrFail($this->editingId);
            $this->authorize('update', $address);
            $address->update($data);
        } else {
            auth()->user()->addresses()->create($data);
        }

        $this->showForm = false;
    }

    public function delete(Address $address): void
    {
        $this->authorize('delete', $address);

        if ($address->orders()->exists() ?? false) {
            $this->addError('delete', 'Alamat ini sudah dipakai di pesanan, tidak bisa dihapus.');
            return;
        }

        $address->delete();
    }

    public function render()
    {
        return view('livewire.address-manager', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
        ]);
    }
}