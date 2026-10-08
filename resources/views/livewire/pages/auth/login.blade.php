<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-8">
    <div>
        <h1 class="font-display text-2xl font-bold">Masuk ke akun kamu</h1>
        <p class="text-sm text-muted mt-1">
            Belum punya akun?
            <a href="{{ route('register') }}" wire:navigate class="text-primary hover:underline">Daftar</a>
        </p>
    </div>

    @if (session('status'))
        <div class="border border-success/30 bg-success/5 text-success text-sm px-4 py-3 rounded-sm">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="flex flex-col gap-5">
        <div>
            <label for="email" class="block text-sm font-medium mb-1">Email</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                required
                autofocus
                autocomplete="email"
                placeholder="nama@email.com"
                class="input"
            >
            @error('email') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="text-sm font-medium">Kata sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-primary hover:underline">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <input
                id="password"
                type="password"
                wire:model="password"
                required
                autocomplete="current-password"
                class="input"
            >
            @error('password') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model="remember" class="accent-primary">
            Ingat saya
        </label>

        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Masuk</span>
            <span wire:loading wire:target="login">Memproses...</span>
        </button>
    </form>
</div>