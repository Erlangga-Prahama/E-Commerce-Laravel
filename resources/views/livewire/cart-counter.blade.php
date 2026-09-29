<a href="{{ route('cart.index') }}" class="relative">
    🛒
    @if ($count > 0)
        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            {{ $count }}
        </span>
    @endif
</a>