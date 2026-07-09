<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'VitalFlow Dashboard')</title>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden antialiased">
    <main class="dashboard-shell min-h-screen overflow-x-hidden bg-[#edf5ff] text-[#1f252c]">
        <div class="mx-auto flex min-h-screen max-w-[1280px] items-stretch bg-[#edf5ff] shadow-[0_24px_70px_rgba(14,46,82,0.12)]">
            <x-dashboard.sidebar
                :active="$activeNav ?? 'dashboard'"
                :brand="$sidebarBrand ?? 'Klinik Presisi'"
                :subtitle="$sidebarSubtitle ?? 'Vitals Control'"
                :support="$sidebarSupport ?? true"
                :variant="$sidebarVariant ?? 'dashboard'"
                :sticky="$sidebarSticky ?? false"
            />

            <div class="min-w-0 flex-1">
                <x-dashboard.topbar :title="$topbarTitle ?? 'RSUD Yowari'" :mode="$topbarMode ?? 'dashboard'" />

                <section class="dashboard-content min-h-[calc(100vh-65px)] min-w-0 overflow-visible px-5 pb-24 pt-6 sm:px-8 lg:px-8 lg:py-8 xl:px-8">
                    <x-dashboard.flash-message />
                    @yield('content')
                </section>
            </div>
        </div>
        <x-dashboard.mobile-nav :active="$activeNav ?? 'dashboard'" />
    </main>
</body>
</html>
