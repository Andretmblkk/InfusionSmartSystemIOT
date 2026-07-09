@props([
    'title' => 'RSUD Yowari',
    'mode' => 'dashboard',
])

<header class="flex h-[65px] items-center border-b border-[#d7dfe8] bg-white px-5 sm:px-8">
    <h1 class="text-[19px] font-extrabold tracking-[-0.02em] {{ $mode === 'patient-input' ? 'text-[#173b80]' : 'text-[#005596]' }}">{{ $title }}</h1>

    <div class="ml-auto hidden items-center sm:flex">
        @if ($mode === 'patient-input')
            <div class="mr-5 text-right">
                <p class="text-[14px] font-extrabold leading-4 text-[#1152e8]">Perawat</p>
                <p class="mt-[6px] text-[10px] font-medium text-[#66758a]">12 Okt 2023, 08:00</p>
            </div>
        @else
            <div class="mr-8 text-right">
                <p class="text-[14px] font-extrabold leading-4 text-[#005596]">Shift: Pagi (07:00 - 14:00)</p>
                <p class="mt-[6px] text-[10px] font-medium uppercase tracking-[0.2em] text-[#777f88]">Senin, 24 Mei 2024 | 09:45 WIB</p>
            </div>
        @endif

        <button type="button" class="relative flex size-10 items-center justify-center text-[#5e646c]">
            <x-ui.icon name="bell" class="size-[22px]" />
            @if ($mode !== 'patient-input')
                <span class="absolute right-[9px] top-[8px] size-[7px] rounded-full bg-[#c91e1e] ring-2 ring-white"></span>
            @endif
        </button>

        @if ($mode === 'patient-input')
            <button type="button" class="ml-1 flex size-10 items-center justify-center text-[#5e646c]">
                <x-ui.icon name="user-circle" class="size-[24px]" />
            </button>
        @else
            <button type="button" class="ml-4 flex size-10 items-center justify-center text-[#5e646c]">
                <x-ui.icon name="settings" class="size-[22px]" />
            </button>
        @endif

        @if ($mode !== 'patient-input')
            <div class="ml-4 h-8 w-px bg-[#d7dfe8]"></div>

            <div class="ml-4 text-right">
                <p class="text-[12px] font-extrabold leading-4 text-[#1f252c]">Suster Amira</p>
                <p class="text-[10px] text-[#777f88]">Perawat Senior</p>
            </div>

            <div class="ml-3 flex size-8 items-center justify-center rounded-full bg-[#b9e7e0] text-[18px]">
                <span aria-hidden="true">👩‍⚕️</span>
            </div>
        @endif
    </div>
</header>
