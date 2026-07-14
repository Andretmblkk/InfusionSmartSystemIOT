@props([
    'active' => 'dashboard',
])

@php
    $items = [
        ['key' => 'dashboard', 'label' => 'Beranda', 'icon' => 'grid', 'href' => route('dashboard')],
        ['key' => 'monitoring', 'label' => 'Monitoring', 'icon' => 'iv', 'href' => route('monitoring')],
        ['key' => 'master-data', 'label' => 'Pasien', 'icon' => 'id-card', 'href' => route('master-data.index')],
        ['key' => 'patient-input', 'label' => 'Input', 'icon' => 'user-plus', 'href' => route('patients.create')],
        ['key' => 'history', 'label' => 'Laporan', 'icon' => 'history', 'href' => route('monitoring.history')],
    ];

    if (auth()->check() && auth()->user()->can('use-operator-panel')) {
        $items[] = ['key' => 'operator', 'label' => 'Operator', 'icon' => 'settings', 'href' => route('operator-panel.index')];
    }
@endphp

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-[#d9e2ec] bg-white/95 px-3 py-2 shadow-[0_-12px_28px_rgba(23,59,128,0.12)] backdrop-blur lg:hidden">
    <div class="mx-auto grid max-w-[520px] gap-1" style="grid-template-columns: repeat({{ count($items) }}, minmax(0, 1fr));">
        @foreach ($items as $item)
            <a href="{{ $item['href'] }}" class="flex min-h-[54px] flex-col items-center justify-center gap-1 rounded-[7px] px-1 text-[10px] font-extrabold {{ $active === $item['key'] ? 'bg-[#eaf2ff] text-[#1152e8]' : 'text-[#526173]' }}">
                <x-ui.icon :name="$item['icon']" class="size-5" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
