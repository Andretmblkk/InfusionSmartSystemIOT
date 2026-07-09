<footer class="mt-12 border-t border-[#dfe7ef] pt-7">
    <div class="flex flex-col gap-5 text-[14px] font-medium text-[#444b55] sm:flex-row sm:items-center sm:justify-between">
        <p>Sistem Monitoring VitalFlow v2.4.0 — RSUD Yowari</p>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-9">
            <div class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#4a4f59]">
                <x-ui.icon name="cloud" class="size-[15px] text-[#007a3d]" />
                <span>Server Terhubung</span>
            </div>

            <a href="{{ route('patients.create') }}" class="inline-flex h-14 items-center justify-center gap-3 rounded-[7px] bg-[#005daa] px-[17px] text-[16px] font-extrabold text-white shadow-[0_14px_28px_rgba(0,83,164,0.26)]">
                <span class="flex size-5 items-center justify-center rounded-full bg-white text-[#005daa]">
                    <x-ui.icon name="plus" class="size-[14px]" />
                </span>
                Pasien Baru
            </a>
        </div>
    </div>
</footer>
