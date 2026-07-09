@props([
    'type' => 'button',
])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex h-14 w-full items-center justify-center gap-2 rounded-[7px] bg-[#005daa] px-6 text-[16px] font-semibold text-white transition hover:bg-[#00539a] focus:outline-none focus:ring-4 focus:ring-[#005daa]/20 auth-button-shadow',
    ]) }}
>
    {{ $slot }}
</button>
