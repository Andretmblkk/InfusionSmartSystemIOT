@props([
    'icon',
    'title',
    'description',
    'tone' => 'blue',
])

@php
    $iconColor = $tone === 'green' ? 'text-[#00843d]' : 'text-[#005daa]';
@endphp

<div class="flex items-start gap-[10px]">
    <x-ui.icon :name="$icon" class="mt-[1px] size-5 {{ $iconColor }}" />
    <div>
        <p class="text-[14px] font-bold leading-4 text-[#1f252c]">{{ $title }}</p>
        <p class="mt-1 max-w-[190px] text-[14px] leading-[20px] text-[#4d5561]">{{ $description }}</p>
    </div>
</div>
