@props([
    'title' => 'RSUD Yowari',
    'mode' => 'dashboard',
])

@php
    $userName = $topbarUser?->name ?? 'Perawat';
    $employeeLabel = $topbarUser?->employee_id ? 'ID ' . $topbarUser->employee_id : 'Petugas aktif';
    $roleLabel = 'Perawat';
    $currentTimeLabel = ($topbarRenderedAt ?? now())->locale('id')->translatedFormat('d M Y, H:i');
    $notifications = $topbarNotifications ?? [];
    $notificationCount = $topbarNotificationCount ?? 0;
    $titleColor = $mode === 'patient-input' ? 'text-[#173b80]' : 'text-[#005596]';
@endphp

<header class="flex h-[65px] items-center border-b border-[#d7dfe8] bg-white px-5 sm:px-8">
    <h1 class="text-[19px] font-extrabold tracking-[-0.02em] {{ $titleColor }}">{{ $title }}</h1>

    <div class="ml-auto hidden items-center gap-2 sm:flex">
        <div class="mr-2 text-right">
            <p class="text-[14px] font-extrabold leading-4 text-[#1152e8]">{{ $userName }}</p>
            <p class="mt-[6px] text-[10px] font-medium text-[#66758a]" data-live-clock>{{ $currentTimeLabel }} WIT</p>
        </div>

        <details class="relative" data-topbar-dropdown>
            <summary class="relative flex size-10 cursor-pointer list-none items-center justify-center rounded-full text-[#5e646c] transition hover:bg-[#eef4fb] focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 [&::-webkit-details-marker]:hidden">
                <x-ui.icon name="bell" class="size-[22px]" />
                @if ($notificationCount > 0)
                    <span class="absolute right-[6px] top-[6px] flex min-w-[18px] items-center justify-center rounded-full bg-[#c91e1e] px-1.5 py-0.5 text-[10px] font-extrabold leading-none text-white">
                        {{ min($notificationCount, 9) }}{{ $notificationCount > 9 ? '+' : '' }}
                    </span>
                @endif
            </summary>

            <div class="absolute right-0 top-[48px] z-40 w-[340px] rounded-[10px] border border-[#d9e2ec] bg-white p-3 shadow-[0_18px_40px_rgba(36,73,110,0.16)]">
                <div class="flex items-center justify-between px-2 pb-3">
                    <div>
                        <p class="text-[13px] font-extrabold text-[#1f252c]">Notifikasi Monitoring</p>
                        <p class="mt-1 text-[11px] font-medium text-[#6d7886]">Peringatan dari bed yang sedang aktif.</p>
                    </div>
                    <span class="rounded-full bg-[#eef4fb] px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.12em] text-[#1152e8]">
                        {{ $notificationCount }}
                    </span>
                </div>

                <div class="max-h-[320px] space-y-2 overflow-y-auto pr-1">
                    @forelse ($notifications as $notification)
                        <a href="{{ $notification['href'] }}" class="block rounded-[8px] border border-[#edf1f5] px-3 py-3 transition hover:border-[#cfe0f7] hover:bg-[#f8fbff]">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[12px] font-extrabold uppercase tracking-[0.12em] text-[#b87400]">{{ $notification['label'] }}</p>
                                    <p class="mt-1 text-[14px] font-extrabold text-[#1f252c]">{{ $notification['patient'] }}</p>
                                    <p class="mt-1 text-[12px] font-medium leading-5 text-[#607081]">{{ $notification['room'] }}</p>
                                    <p class="mt-2 text-[12px] font-medium leading-5 text-[#4d5a68]">{{ $notification['message'] }}</p>
                                </div>
                                <span class="shrink-0 rounded-full bg-[#fff4d6] px-2 py-1 text-[10px] font-extrabold text-[#a76500]">
                                    {{ $notification['percentage'] }}%
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-[8px] border border-dashed border-[#d9e2ec] px-4 py-6 text-center">
                            <p class="text-[13px] font-extrabold text-[#1f252c]">Tidak ada notifikasi aktif</p>
                            <p class="mt-1 text-[12px] font-medium text-[#6d7886]">Semua monitoring sedang normal atau belum ada peringatan baru.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pt-3">
                    <a href="{{ route('monitoring') }}" class="inline-flex h-10 w-full items-center justify-center rounded-[8px] bg-[#005daa] px-4 text-[13px] font-extrabold text-white shadow-[0_10px_20px_rgba(0,83,164,0.18)]">
                        Buka Monitoring
                    </a>
                </div>
            </div>
        </details>

        <details class="relative" data-topbar-dropdown>
            <summary class="flex cursor-pointer list-none items-center gap-3 rounded-[999px] pl-2 pr-1.5 transition hover:bg-[#eef4fb] focus:outline-none focus:ring-4 focus:ring-[#1152e8]/20 [&::-webkit-details-marker]:hidden">
                <div class="text-right">
                    <p class="text-[12px] font-extrabold leading-4 text-[#1f252c]">{{ $userName }}</p>
                    <p class="mt-1 text-[10px] text-[#777f88]">{{ $roleLabel }}</p>
                </div>
                <div class="flex size-9 items-center justify-center rounded-full bg-[#e3edf8] text-[#1152e8]">
                    <x-ui.icon name="user-circle" class="size-[22px]" />
                </div>
            </summary>

            <div class="absolute right-0 top-[52px] z-40 w-[260px] rounded-[10px] border border-[#d9e2ec] bg-white p-3 shadow-[0_18px_40px_rgba(36,73,110,0.16)]">
                <div class="rounded-[8px] bg-[#f5f8fc] px-4 py-3">
                    <p class="text-[14px] font-extrabold text-[#1f252c]">{{ $userName }}</p>
                    <p class="mt-1 text-[11px] font-medium text-[#6b7785]">{{ $employeeLabel }}</p>
                    <p class="mt-1 text-[11px] font-medium text-[#6b7785]">{{ $roleLabel }} aktif</p>
                </div>

                <div class="mt-3 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-between rounded-[8px] px-3 py-2 text-[13px] font-semibold text-[#1f252c] transition hover:bg-[#f5f8fc]">
                        <span>Beranda</span>
                        <span class="text-[#7f8a97]">/dashboard</span>
                    </a>
                    <a href="{{ route('monitoring.history') }}" class="flex items-center justify-between rounded-[8px] px-3 py-2 text-[13px] font-semibold text-[#1f252c] transition hover:bg-[#f5f8fc]">
                        <span>Laporan Monitoring</span>
                        <span class="text-[#7f8a97]">/laporan</span>
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-[8px] bg-[#d71920] px-4 text-[13px] font-extrabold text-white shadow-[0_10px_20px_rgba(215,25,32,0.16)]">
                        Keluar dari Sistem
                    </button>
                </form>
            </div>
        </details>
    </div>
</header>
