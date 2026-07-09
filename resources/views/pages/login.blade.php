@extends('layouts.auth')

@section('title', 'Masuk - VitalFlow')

@section('content')
    <section class="mx-auto grid min-h-0 w-full max-w-[1150px] grid-cols-1 overflow-hidden rounded-[7px] bg-white/78 shadow-[0_30px_80px_rgba(20,50,80,0.08)] ring-1 ring-white/70 lg:h-[calc(100vh-32px)] lg:max-h-[814px] lg:grid-cols-[1fr_1fr]">
        <div class="min-h-0 bg-[#eaf0f4]/82 px-6 py-8 sm:px-12 lg:overflow-hidden lg:px-12 lg:py-10 xl:px-12 xl:py-12">
            <div class="mx-auto max-w-[480px] lg:mx-0">
                <x-auth.brand-mark />

                <div class="mt-6">
                    <h1 class="max-w-[430px] text-[34px] font-extrabold leading-[1.43] tracking-[-0.02em] text-[#1f252c]">
                        Presisi Klinis untuk
                        <span class="block text-[#00733d]">Pelayanan Utama.</span>
                    </h1>

                    <p class="mt-[17px] max-w-[330px] text-[19px] leading-[1.55] text-[#444b55] sm:max-w-[365px]">
                        Selamat datang kembali di portal RSUD Yowari. Silakan masuk untuk memantau aliran vital pasien Anda dengan tenang dan akurat.
                    </p>
                </div>

                <div class="mt-[2px] grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-[44px]">
                    <x-auth.feature-item
                        icon="shield"
                        title="Akses Terenkripsi"
                        description="Standar keamanan medis HIPAA"
                    />
                    <x-auth.feature-item
                        icon="monitor"
                        title="Pantauan Real-time"
                        description="Sinkronisasi IoT instan"
                        tone="green"
                    />
                </div>

                <x-auth.promo-card />
            </div>
        </div>

        <div class="flex min-h-0 px-6 py-8 sm:px-12 lg:overflow-hidden lg:px-16 lg:py-10 xl:px-16">
            <div class="mx-auto flex min-h-0 w-full max-w-[447px] flex-col pt-0 lg:mx-0 xl:pt-[19px]">
                <div>
                    <h2 class="text-[25px] font-extrabold leading-tight tracking-[-0.01em] text-[#1f252c]">Masuk ke Sistem</h2>
                    <p class="mt-3 text-[17px] leading-6 text-[#4d5561]">Gunakan akun resmi rumah sakit Anda</p>
                </div>

                <form class="mt-7 space-y-5 xl:mt-[38px] xl:space-y-[24px]" method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <x-auth.shift-select />

                    <x-ui.field
                        label="NIP / ID Karyawan"
                        name="employee_id"
                        icon="id-card"
                        placeholder="Masukkan NIP Anda"
                        :value="old('employee_id')"
                    />

                    <x-ui.field
                        label="Kata Sandi"
                        name="password"
                        type="password"
                        icon="lock"
                    >
                        <x-slot:action>
                            <a href="#" class="text-[12px] font-bold text-[#005daa]">Lupa Sandi?</a>
                        </x-slot:action>
                    </x-ui.field>

                    @error('employee_id')
                        <p class="-mt-2 text-[12px] font-semibold text-[#c91e1e]">{{ $message }}</p>
                    @enderror

                    <label class="flex w-fit items-center gap-[10px] pt-2">
                        <input
                            type="checkbox"
                            name="remember_shift"
                            class="size-5 rounded-[2px] border border-[#bcc7d6] bg-[#dfe6ef] text-[#005daa] focus:ring-[#005daa]/20"
                        >
                        <span class="text-[14px] font-medium text-[#4d5561]">Tetap masuk selama shift ini</span>
                    </label>

                    <div class="pt-2 xl:pt-3">
                        <x-ui.button type="submit">
                            Masuk Sekarang
                            <x-ui.icon name="arrow-right" class="size-6" />
                        </x-ui.button>
                    </div>
                </form>

                <footer class="mt-auto pb-2 pt-8 xl:pb-[22px] xl:pt-16">
                    <div class="h-px bg-[#d7dce2]"></div>

                    <div class="mt-[33px] flex items-center justify-center gap-4">
                        <span class="flex size-8 items-center justify-center rounded-full bg-white text-[#8a8f97] shadow-[0_8px_16px_rgba(20,50,80,0.05)]">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M8 12h8M12 8v8M6.5 16.5h11l1.5-5.5-3-4H8l-3 4 1.5 5.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#5d6470]">Dikelola Oleh</p>
                            <p class="mt-[2px] text-[12px] font-semibold text-[#5d6470]">Sistem Informasi Medis RSUD Yowari</p>
                        </div>
                    </div>

                    <nav class="mt-[30px] flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-[9px] font-extrabold uppercase text-[#697382]">
                        <a href="#">Kebijakan Privasi</a>
                        <span>•</span>
                        <a href="#">Bantuan Teknis</a>
                        <span>•</span>
                        <span>v2.4.0</span>
                    </nav>
                </footer>
            </div>
        </div>
    </section>
@endsection
