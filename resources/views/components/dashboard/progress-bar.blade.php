@props([
    'tone' => 'green',
    'value' => 70,
])

@php
    $bar = [
        'green' => 'bg-[#007a3d]',
        'red' => 'bg-[#c91e1e]',
        'cyan' => 'bg-[#008a9a]',
    ][$tone];
@endphp

<div class="mt-[7px] h-[5px] w-24 overflow-hidden rounded-full bg-[#dfe4e8]">
    <div class="h-full rounded-full {{ $bar }}" style="width: {{ $value }}%"></div>
</div>
