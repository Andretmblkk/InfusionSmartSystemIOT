@props([
    'name',
    'class' => 'size-5',
])

@switch($name)
    @case('droplet')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3.5c2.9 3.28 5.25 6.12 5.25 9.03a5.25 5.25 0 1 1-10.5 0c0-2.91 2.35-5.75 5.25-9.03Z" fill="currentColor"/>
            <path d="M9.6 13.1c.1 1.52 1.08 2.46 2.35 2.62" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        @break
    @case('shield')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3.4 18.7 6v5.15c0 4.35-2.8 7.82-6.7 9.45-3.9-1.63-6.7-5.1-6.7-9.45V6L12 3.4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('monitor')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 6.5h16v10H4v-10Z" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/>
            <path d="M7 13h3l1.35-3.2 2.3 5.25L15.3 12H18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('id-card')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 7h14v11H5V7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M9 7V5h6v2M9.2 15.2c.25-1.05 1.05-1.65 2-1.65s1.75.6 2 1.65M11.2 12.25a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5ZM15.8 11h1.6M15.8 14h1.6" stroke="currentColor" stroke-width="1.55" stroke-linecap="round"/>
        </svg>
        @break
    @case('lock')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M7 10h10v9H7v-9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M9.2 10V7.8a2.8 2.8 0 0 1 5.6 0V10M12 14v1.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        @break
    @case('eye')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3.8 12s2.9-5 8.2-5 8.2 5 8.2 5-2.9 5-8.2 5-8.2-5-8.2-5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M12 14.45a2.45 2.45 0 1 0 0-4.9 2.45 2.45 0 0 0 0 4.9Z" stroke="currentColor" stroke-width="1.8"/>
        </svg>
        @break
    @case('arrow-right')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h13M13 6.5l5.5 5.5-5.5 5.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('grid')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 4h6v6H4V4ZM14 4h6v6h-6V4ZM4 14h6v6H4v-6ZM14 14h6v6h-6v-6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
        </svg>
        @break
    @case('user-plus')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9.5 12.2a3.7 3.7 0 1 0 0-7.4 3.7 3.7 0 0 0 0 7.4ZM3.8 19.2c.7-3.1 2.8-4.8 5.7-4.8 1.7 0 3.1.55 4.1 1.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M18 8v6M15 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        @break
    @case('iv')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M8 4h8v4a4 4 0 0 1-8 0V4ZM12 12v8M9 20h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 8h2.2c1 0 1.8.8 1.8 1.8V14c0 1.1.9 2 2 2M10.4 6.7h3.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        @break
    @case('history')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12a8 8 0 1 0 2.35-5.65L4 8.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4 4.8v3.9h3.9M12 8v4l2.6 2.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('bell')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 18h12l-1.4-2.2V11a4.6 4.6 0 0 0-9.2 0v4.8L6 18ZM10 20h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('settings')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 15.2a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4Z" stroke="currentColor" stroke-width="1.8"/>
            <path d="m18.4 13.6 1.5 1.15-1.7 2.95-1.8-.75a7.5 7.5 0 0 1-1.5.85L14.65 20h-3.3l-.25-2.2a7.5 7.5 0 0 1-1.5-.85l-1.8.75-1.7-2.95 1.5-1.15a7.1 7.1 0 0 1 0-1.2l-1.5-1.15L7.8 8.3l1.8.75c.47-.34.97-.63 1.5-.85l.25-2.2h3.3l.25 2.2c.53.22 1.03.51 1.5.85l1.8-.75 1.7 2.95-1.5 1.15c.04.4.04.8 0 1.2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
        </svg>
        @break
    @case('logout')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M10 5H5v14h5M14 8l4 4-4 4M8 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('dots')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 6.5v.01M12 12v.01M12 17.5v.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
        </svg>
        @break
    @case('plus')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
        </svg>
        @break
    @case('info')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" stroke="currentColor" stroke-width="2"/>
            <path d="M12 10.5v5M12 7.5v.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
        </svg>
        @break
    @case('clipboard-user')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M8 5h8v3H8V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M6 7h12v13H6V7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M9.3 16.4c.25-1.08 1.05-1.7 2.2-1.7s1.95.62 2.2 1.7M11.5 13.2a1.35 1.35 0 1 0 0-2.7 1.35 1.35 0 0 0 0 2.7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        @break
    @case('play-circle')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" stroke="currentColor" stroke-width="1.9"/>
            <path d="m10.2 8.8 5 3.2-5 3.2V8.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
        </svg>
        @break
    @case('wifi')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 10.2a10.5 10.5 0 0 1 14 0M8 13.2a6 6 0 0 1 8 0M11 16.2a1.5 1.5 0 0 1 2 0M12 19v.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        @break
    @case('signal')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 18v-3M10 18v-6M14 18V9M18 18V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        @break
    @case('battery')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 8h12v8H5V8ZM19 10.5v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 11v2M11 10.2v3.6M14 11v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break
    @case('user-circle')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" stroke="currentColor" stroke-width="1.8"/>
            <path d="M8.2 17c.55-2 1.9-3.05 3.8-3.05S15.25 15 15.8 17M12 11.6a2.45 2.45 0 1 0 0-4.9 2.45 2.45 0 0 0 0 4.9Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        @break
    @case('circle-check')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" stroke="currentColor" stroke-width="1.8"/>
            <path d="m8.8 12.2 2 2 4.4-4.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('cloud')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M8 18h9a3.4 3.4 0 0 0 .55-6.76A5.15 5.15 0 0 0 7.7 9.55 3.98 3.98 0 0 0 8 18Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="m9.5 13.7 1.7 1.7 3.5-3.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @case('flow')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h6M13 9l3 3-3 3M17 12h2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M6.5 8.5h3M6.5 15.5h3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        </svg>
        @break
    @default
        <span {{ $attributes->merge(['class' => $class]) }}></span>
@endswitch
