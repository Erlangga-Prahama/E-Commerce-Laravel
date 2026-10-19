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
    {{-- Header --}}
    <div class="flex flex-col gap-4">
        <div class="inline-flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary ring-1 ring-primary/20">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
            </svg>
        </div>

        <div>
            <h1 class="font-display text-3xl font-bold tracking-tight">Buat akun baru</h1>
            <p class="text-sm text-muted mt-2">
                Daftar untuk mulai belanja dan melacak pesananmu.
            </p>
        </div>
    </div>

    {{-- Form --}}
    <form wire:submit="register" class="flex flex-col gap-5">
        {{-- Nama --}}
        <div>
            <label for="name" class="block text-sm font-medium mb-1.5">Nama lengkap</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </span>
                <input id="name" type="text" wire:model="name" required autofocus
                       autocomplete="name" autocapitalize="words" placeholder="Nama kamu"
                       class="input pl-11 rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                              @error('name') border-danger focus:ring-danger/30 @enderror">
            </div>
            @error('name')
                <p class="flex items-center gap-1.5 text-danger text-sm mt-1.5">
                    <svg class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </span>
                <input id="email" type="email" wire:model="email" required
                       autocomplete="username" autocapitalize="none" inputmode="email" placeholder="nama@email.com"
                       class="input pl-11 rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                              @error('email') border-danger focus:ring-danger/30 @enderror">
            </div>
            @error('email')
                <p class="flex items-center gap-1.5 text-danger text-sm mt-1.5">
                    <svg class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password + konfirmasi (satu toggle untuk keduanya) --}}
        <div class="flex flex-col gap-5"
             x-data="{
                 show: false,
                 pw: '',
                 pc: '',
                 get score() {
                     let s = 0;
                     if (this.pw.length >= 8) s++;
                     if (/[a-z]/.test(this.pw) && /[A-Z]/.test(this.pw)) s++;
                     if (/\d/.test(this.pw)) s++;
                     if (/[^A-Za-z0-9]/.test(this.pw)) s++;
                     return this.pw.length ? Math.max(s, 1) : 0;
                 },
                 get label() { return ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'][this.score]; },
                 get barColor() { return this.score <= 1 ? 'bg-danger' : (this.score === 2 ? 'bg-accent' : 'bg-success'); }
             }">
            <div>
                <label for="password" class="block text-sm font-medium mb-1.5">Kata sandi</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </span>
                    <input id="password" :type="show ? 'text' : 'password'" wire:model="password" x-model="pw" required
                           autocomplete="new-password" placeholder="Minimal 8 karakter"
                           class="input pl-11 pr-12 rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                                  @error('password') border-danger focus:ring-danger/30 @enderror">

                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-muted hover:text-primary transition"
                            :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                        <svg x-show="!show" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>

                {{-- Indikator kekuatan --}}
                <div class="mt-2.5" x-show="pw.length" x-cloak>
                    <div class="flex gap-1.5">
                        <template x-for="i in 4" :key="i">
                            <span class="h-1.5 flex-1 rounded-full transition-colors duration-300"
                                  :class="i <= score ? barColor : 'bg-line'"></span>
                        </template>
                    </div>
                    <p class="text-xs text-muted mt-1.5">
                        Kekuatan: <span class="font-medium text-ink" x-text="label"></span>
                    </p>
                </div>

                @error('password')
                    <p class="flex items-center gap-1.5 text-danger text-sm mt-1.5">
                        <svg class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1.5">Ulangi kata sandi</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </span>
                    <input id="password_confirmation" :type="show ? 'text' : 'password'"
                           wire:model="password_confirmation" x-model="pc" required
                           autocomplete="new-password" placeholder="Ketik ulang kata sandi"
                           class="input pl-11 rounded-lg text-base transition focus:ring-2 focus:ring-primary/30
                                  @error('password_confirmation') border-danger focus:ring-danger/30 @enderror">
                </div>

                <p class="text-xs mt-1.5 text-success" x-show="pc.length && pc === pw" x-cloak>
                    ✓ Kata sandi cocok
                </p>
                <p class="text-xs mt-1.5 text-danger" x-show="pc.length && pc !== pw" x-cloak>
                    Kata sandi belum sama
                </p>

                @error('password_confirmation')
                    <p class="flex items-center gap-1.5 text-danger text-sm mt-1.5">
                        <svg class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="btn-primary w-full rounded-lg py-3 inline-flex items-center justify-center gap-2 shadow-sm
                       transition hover:shadow-md active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed"
                wire:loading.attr="disabled" wire:target="register">
            <svg wire:loading wire:target="register" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span wire:loading.remove wire:target="register">Daftar</span>
            <span wire:loading wire:target="register">Memproses...</span>
            <svg wire:loading.remove wire:target="register" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </button>
    </form>

    {{-- Divider + login --}}
    <div class="flex flex-col gap-5">
        <div class="flex items-center gap-3 text-xs text-muted uppercase tracking-wider">
            <span class="h-px flex-1 bg-current opacity-20"></span>
            <span>atau</span>
            <span class="h-px flex-1 bg-current opacity-20"></span>
        </div>

        <a href="{{ route('login') }}" wire:navigate
           class="w-full inline-flex items-center justify-center rounded-lg border border-current/20 px-4 py-3 text-sm font-medium
                  transition hover:border-primary hover:text-primary hover:bg-primary/5">
            Sudah punya akun? <span class="text-primary ml-1">Masuk</span>
        </a>
    </div>
</div>