@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'icon' => null,
    'action' => null,
    'value' => null,
])

<div class="space-y-2">
    <div class="flex items-center justify-between">
        <label for="{{ $name }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-[#4d5561]">
            {{ $label }}
        </label>

        @if ($action)
            {{ $action }}
        @endif
    </div>

    <div class="relative">
        @if ($icon)
            <x-ui.icon :name="$icon" class="pointer-events-none absolute left-[15px] top-1/2 size-5 -translate-y-1/2 text-[#444b55]" />
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @if ($type === 'password') data-password-input @endif
            {{ $attributes->merge([
                'class' => 'auth-input-shadow h-12 w-full rounded-[7px] border-0 bg-[#e9ecef] pl-12 pr-12 text-[16px] font-medium text-[#1f252c] placeholder:text-[#c3c8d4] focus:outline-none focus:ring-4 focus:ring-[#005daa]/15',
            ]) }}
        >

        @if ($type === 'password')
            <button
                type="button"
                class="absolute right-[12px] top-1/2 inline-flex size-8 -translate-y-1/2 items-center justify-center rounded-full text-[#444b55] transition hover:bg-white/70 focus:outline-none focus:ring-4 focus:ring-[#005daa]/15"
                data-password-toggle
                aria-label="Tampilkan kata sandi"
                aria-pressed="false"
            >
                <x-ui.icon name="eye" class="size-5" />
            </button>
        @endif
    </div>
</div>
