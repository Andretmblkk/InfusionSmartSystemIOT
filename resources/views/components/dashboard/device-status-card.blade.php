<aside class="rounded-[7px] bg-white px-6 py-6 shadow-[0_8px_24px_rgba(38,74,112,0.05)]">
    <div class="flex items-center justify-between">
        <h2 class="text-[12px] font-extrabold uppercase tracking-[0.16em] text-[#4c5563]">Status Perangkat</h2>
        <span class="inline-flex h-5 items-center rounded-full bg-[#64ee89] px-3 text-[12px] font-semibold text-[#007a3d]">Online</span>
    </div>

    <div class="mt-4 space-y-4">
        <div class="flex h-[54px] items-center justify-between rounded-[4px] bg-[#dfe4eb] px-3">
            <div>
                <p class="text-[10px] font-extrabold uppercase text-[#748095]">MAC Address</p>
                <p class="mt-[3px] text-[12px] font-extrabold text-[#005596]">VF:09:A4:B2:77</p>
            </div>
            <x-ui.icon name="wifi" class="size-5 text-[#7e8ea5]" />
        </div>

        <div class="flex h-[54px] items-center justify-between rounded-[4px] bg-[#dfe4eb] px-3">
            <div>
                <p class="text-[10px] font-extrabold uppercase text-[#748095]">Kekuatan Sinyal</p>
                <p class="mt-[3px] text-[12px] font-extrabold text-[#1f252c]">-45 dBm (Sangat Baik)</p>
            </div>
            <x-ui.icon name="signal" class="size-5 text-[#00824a]" />
        </div>

        <div class="flex h-[54px] items-center justify-between rounded-[4px] bg-[#dfe4eb] px-3">
            <div>
                <p class="text-[10px] font-extrabold uppercase text-[#748095]">Baterai</p>
                <p class="mt-[3px] text-[12px] font-extrabold text-[#1f252c]">92%</p>
            </div>
            <x-ui.icon name="battery" class="size-5 text-[#00824a]" />
        </div>
    </div>
</aside>
