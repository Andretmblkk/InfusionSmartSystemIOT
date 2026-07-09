@props([
    'patients' => [],
    'total' => 0,
    'paginator' => null,
])

<section class="overflow-hidden rounded-[7px] bg-white shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
    <div class="dashboard-table-header flex h-[72px] items-center border-b border-[#e8edf2] px-8">
        <h3 class="text-[16px] font-extrabold text-[#1f252c]">Pemantauan Real-time</h3>
        <div class="ml-auto flex items-center gap-[7px] text-[12px] font-semibold text-[#007a3d]">
            <span class="size-2 rounded-full bg-[#007a3d]"></span>
            Sistem Terhubung
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[820px] border-collapse">
            <thead>
                <tr class="dashboard-table-head-row h-[50px] bg-[#fbfcfd] text-left text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#a0a5aa]">
                    <th class="pl-8 pr-4">Informasi Pasien</th>
                    <th class="px-4">Lokasi</th>
                    <th class="px-4">Status Infus</th>
                    <th class="px-4">Sisa Infus</th>
                    <th class="px-4">Berat Terbaca</th>
                    <th class="pl-4 pr-7 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patients as $patient)
                    <x-dashboard.patient-row
                        :initials="$patient['initials']"
                        :name="$patient['name']"
                        :meta="$patient['meta']"
                        :room="$patient['room']"
                        :status="$patient['status']"
                        :status-tone="$patient['statusTone']"
                        :current-weight="$patient['currentWeight']"
                        :empty-weight="$patient['emptyWeight']"
                        :full-weight="$patient['fullWeight']"
                        :weight-tone="$patient['weightTone']"
                        :avatar-tone="$patient['avatarTone']"
                        :href="$patient['href'] ?? null"
                        :time-remaining="$patient['timeRemaining'] ?? null"
                    />
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-[14px] font-semibold text-[#6b7480]">
                            Belum ada pasien aktif. Tambahkan pasien baru untuk mulai monitoring.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="dashboard-table-footer flex h-[62px] items-center px-8">
        <p class="text-[11px] text-[#8b929a]">Menampilkan {{ count($patients) }} dari {{ $total }} pasien aktif</p>
        <div class="ml-auto flex gap-2">
            @if ($paginator && $paginator->onFirstPage())
                <span class="inline-flex h-8 items-center rounded-[5px] bg-[#e2e7ed] px-4 text-[11px] font-extrabold text-[#9aa3ad]">Sebelumnya</span>
            @elseif ($paginator)
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-8 items-center rounded-[5px] bg-[#e2e7ed] px-4 text-[11px] font-extrabold text-[#4f5964]">Sebelumnya</a>
            @endif

            @if ($paginator && $paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-8 items-center rounded-[5px] bg-[#005596] px-5 text-[11px] font-extrabold text-white">Berikutnya</a>
            @elseif ($paginator)
                <span class="inline-flex h-8 items-center rounded-[5px] bg-[#005596]/45 px-5 text-[11px] font-extrabold text-white">Berikutnya</span>
            @endif
        </div>
    </div>
</section>
