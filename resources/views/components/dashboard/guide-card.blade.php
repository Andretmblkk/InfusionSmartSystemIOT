<aside class="rounded-[7px] bg-[#005596] px-6 py-7 text-white shadow-[0_12px_24px_rgba(0,85,150,0.12)]">
    <h2 class="text-[19px] font-extrabold">Panduan Monitoring</h2>

    <div class="mt-5 space-y-5">
        @foreach ([
            'Pastikan bed yang dipilih sesuai dengan alat monitoring di sisi pasien.',
            'Volume infus awal harus sesuai dengan label pada kantong cairan infus.',
            'Gunakan format waktu 24 jam untuk presisi penghitungan estimasi habis.',
            'Sistem akan memberikan notifikasi kritis jika sisa cairan di bawah 10%.',
        ] as $index => $item)
            <div class="flex gap-3">
                <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-white/20 text-[11px] font-extrabold">{{ $index + 1 }}</span>
                <p class="text-[15px] leading-[1.5] text-white/95">{{ $item }}</p>
            </div>
        @endforeach
    </div>
</aside>
