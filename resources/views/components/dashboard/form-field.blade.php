@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => null,
    'suffix' => null,
])

<div class="space-y-[9px]">
    <label for="{{ $name }}" class="text-[12px] font-extrabold uppercase tracking-[0.13em] text-[#4c5563]">
        {{ $label }}
    </label>

    <div class="relative">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}_error" @enderror
            {{ $attributes->merge([
                'class' => 'h-12 w-full rounded-[6px] border-0 bg-[#dfe4eb] px-4 text-[16px] font-medium text-[#1f252c] caret-[#1f252c] placeholder:text-[#7b8493] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 ' . ($suffix ? 'pr-12' : '') . ($errors->has($name) ? ' ring-2 ring-[#c91e1e]/35' : ''),
            ]) }}
        >

        @if ($suffix)
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[13px] font-bold text-[#7a8aa2]">{{ $suffix }}</span>
        @endif
    </div>

    @error($name)
        <p id="{{ $name }}_error" class="text-[12px] font-bold text-[#c91e1e]">{{ $message }}</p>
    @enderror
</div>
