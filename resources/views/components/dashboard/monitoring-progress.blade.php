@props([
    'value' => 0,
    'tone' => 'green',
])

@php
    $barClasses = [
        'green' => 'bg-[#007a3d]',
        'yellow' => 'bg-[#e9b400]',
        'red' => 'bg-[#c91e1e]',
    ][$tone];
@endphp

<div class="h-3 w-full overflow-hidden rounded-full bg-[#dfe6ec]">
    <div class="h-full rounded-full {{ $barClasses }}" style="width: {{ $value }}%"></div>
</div>
