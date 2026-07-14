@props([
    'active' => 'dashboard',
    'brand' => 'Klinik Presisi',
    'subtitle' => 'Vitals Control',
    'support' => true,
    'variant' => 'dashboard',
    'sticky' => false,
])

@php
    $items = [
        ['key' => 'dashboard', 'label' => 'Beranda', 'icon' => 'grid', 'href' => route('dashboard')],
        ['key' => 'monitoring', 'label' => 'Monitoring Infus', 'icon' => 'iv', 'href' => route('monitoring')],
        ['key' => 'master-data', 'label' => 'Input Data Pasien', 'icon' => 'id-card', 'href' => route('master-data.index')],
        ['key' => 'patient-input', 'label' => 'Input Monitoring', 'icon' => 'user-plus', 'href' => route('patients.create')],
        ['key' => 'history', 'label' => 'Laporan', 'icon' => 'history', 'href' => route('monitoring.history')],
    ];

    if (auth()->check() && auth()->user()->can('use-operator-panel')) {
        $items[] = ['key' => 'operator', 'label' => 'Panel Operator', 'icon' => 'settings', 'href' => route('operator-panel.index')];
    }
@endphp

<aside class="hidden {{ $variant === 'patient-input' ? 'w-[276px]' : 'w-[288px]' }} {{ $sticky ? 'lg:sticky lg:top-0 lg:self-start' : '' }} min-h-screen shrink-0 bg-white px-6 py-[27px] shadow-[inset_-1px_0_0_#e2e7ec] lg:flex lg:flex-col">
    <div>
        <p class="{{ $brand === 'VitalFlow' ? 'text-[22px] font-extrabold normal-case tracking-[-0.02em] text-[#173b80]' : 'text-[12px] font-bold uppercase tracking-[0.34em] text-[#4374ad]' }}">{{ $brand }}</p>
        <p class="mt-[7px] text-[12px] {{ $brand === 'VitalFlow' ? 'font-bold uppercase tracking-[0.22em] text-[#69778b]' : 'text-[#707780]' }}">{{ $subtitle }}</p>
    </div>

    <nav class="mt-8 space-y-4">
        @foreach ($items as $item)
            <a
                href="{{ $item['href'] }}"
                class="flex h-[44px] items-center gap-3 rounded-[7px] px-3 text-[15px] {{ $variant === 'patient-input' ? 'font-bold uppercase tracking-[0.08em]' : 'font-semibold tracking-[0.01em]' }} {{ $active === $item['key'] ? ($variant === 'patient-input' ? 'bg-[#eaf2ff] text-[#1152e8]' : 'bg-[#005596] text-white shadow-[0_8px_16px_rgba(0,85,150,0.22)]') : 'text-[#4f5b6c]' }}"
            >
                <x-ui.icon :name="$item['icon']" class="size-5 shrink-0" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto">
        <div class="h-px bg-[#e7eaee]"></div>

        <form method="POST" action="{{ route('logout') }}" class="mt-[34px]">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 text-[15px] font-semibold text-[#b40000]">
                <x-ui.icon name="logout" class="size-5" />
                <span>{{ $variant === 'patient-input' ? 'Keluar' : 'Logout' }}</span>
            </button>
        </form>

        @if ($support)
            <div class="mt-[47px] rounded-[7px] bg-[#e3e8ee] p-4">
                <p class="text-[12px] text-[#6a727b]">Butuh bantuan?</p>
                <button type="button" class="mt-2 h-8 w-full rounded-[5px] bg-[#005596] text-[12px] font-bold text-white">
                    Bantuan Teknis
                </button>
            </div>
        @endif
    </div>
</aside>
