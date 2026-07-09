@props([
    'icon',
    'title',
    'description',
    'tone' => 'blue',
])

@php
    $toneClass = $tone === 'green'
        ? 'bg-[#64ee89] text-[#007a3d]'
        : 'bg-[#d5e2ff] text-[#173b80]';
@endphp

<div class="flex items-center gap-4">
    <span class="flex size-12 shrink-0 items-center justify-center rounded-[11px] {{ $toneClass }}">
        <x-ui.icon :name="$icon" class="size-6" />
    </span>
    <div>
        <h2 class="text-[21px] font-extrabold leading-6 tracking-[-0.01em] text-[#1f252c]">{{ $title }}</h2>
        <p class="mt-[5px] text-[14px] font-medium text-[#4d5561]">{{ $description }}</p>
    </div>
</div>
