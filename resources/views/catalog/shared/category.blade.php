<a
    href="{{ route('catalog', $item->slug) }}"
    @class([
        'pointer-events-none bg-purple' => $category?->exists && $item->slug === $category?->slug,
        'hover:bg-pink bg-card' => $category?->exists || $item->slug !== $category?->slug,
        'p-3 sm:p-4 2xl:p-6 rounded-xl text-xxs sm:text-xs lg:text-sm text-white font-semibold'
    ])
>
    {{ $item->title }}
</a>
