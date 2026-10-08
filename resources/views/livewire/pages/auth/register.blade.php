<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('products.index', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-8">
    <div>
        <h1 class="font-display text-2xl font-bold">Buat akun baru</h1>
        <p class="text-sm text-muted mt-1">
            Sudah punya akun?
            <a href="{{ route('login') }}" wire:navigate class="text-primary hover:underline">Masuk</a>
        </p>
    </div>

    <form wire:submit="register" class="flex flex-col gap-5">
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nama lengkap</label>
            <input id="name" type="text" wire:model="name" required autofocus
                   autocomplete="name" class="input">
            @error('name') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium mb-1">Email</label>
            <input id="email" type="email" wire:model="email" required
                   autocomplete="username" placeholder="nama@email.com" class="input">
            @error('email') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1">Kata sandi</label>
            <input id="password" type="password" wire:model="password" required
                   autocomplete="new-password" class="input">
            <p class="text-xs text-muted mt-1">Minimal 8 karakter.</p>
            @error('password') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium mb-1">Ulangi kata sandi</label>
            <input id="password_confirmation" type="password" wire:model="password_confirmation" required
                   autocomplete="new-password" class="input">
            @error('password_confirmation') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled" wire:target="register">
            <span wire:loading.remove wire:target="register">Daftar</span>
            <span wire:loading wire:target="register">Memproses...</span>
        </button>
    </form>
</div>