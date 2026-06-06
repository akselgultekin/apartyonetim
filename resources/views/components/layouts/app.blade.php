<!doctype html>
<html lang="tr" class="theme-ready">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Apart Yönetim Paneli' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        [x-cloak] { display: none; }
        :root {
            color-scheme: light;
            --page-bg: #f5f7f4;
            --panel-bg: rgba(255, 255, 255, 0.94);
            --panel-border: #dfe7df;
            --muted-text: #64748b;
            --ink: #172033;
            --brand: #0f766e;
            --brand-strong: #0f3d3e;
            --accent: #d97706;
        }
        .dark {
            color-scheme: dark;
            --page-bg: #0b1220;
            --panel-bg: rgba(17, 24, 39, 0.92);
            --panel-border: #263345;
            --muted-text: #9aa8bc;
            --ink: #e5edf7;
            --brand: #2dd4bf;
            --brand-strong: #134e4a;
            --accent: #fbbf24;
        }
        body {
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .10) 0 1px, transparent 1px 28px),
                linear-gradient(45deg, rgba(217, 119, 6, .07) 0 1px, transparent 1px 32px),
                var(--page-bg);
            color: var(--ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            overflow-x: hidden;
        }
        .shell-sidebar {
            background:
                linear-gradient(135deg, rgba(255,255,255,.08) 0 1px, transparent 1px 26px),
                linear-gradient(180deg, #10233f 0%, #0f3d3e 58%, #182131 100%);
            color: #ecfeff;
        }
        .brand-mark {
            background:
                linear-gradient(145deg, rgba(45, 212, 191, .32), rgba(251, 191, 36, .18)),
                rgba(255,255,255,.08);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.22), 0 12px 26px rgba(0,0,0,.16);
        }
        .nav-item {
            transition: background-color .18s ease, color .18s ease, transform .18s ease;
        }
        .nav-item:hover { transform: translateX(2px); }
        .shell-card {
            background: var(--panel-bg);
            border-color: var(--panel-border);
            box-shadow: 0 18px 50px rgba(15, 23, 42, .06);
            min-width: 0;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .shell-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 60px rgba(15, 23, 42, .10);
        }
        .dark .shell-card { box-shadow: 0 18px 50px rgba(0, 0, 0, .18); }
        .dark .bg-white,
        .dark .bg-slate-50,
        .dark .bg-slate-100 { background-color: rgba(17, 24, 39, .94) !important; }
        .dark .shell-sidebar .bg-white {
            background-color: rgba(255, 255, 255, .95) !important;
            color: #0f172a !important;
        }
        .dark .border-slate-100,
        .dark .border-slate-200,
        .dark .border-slate-300 { border-color: #263345 !important; }
        .dark .text-slate-950,
        .dark .text-slate-900,
        .dark .text-slate-800,
        .dark .text-slate-700 { color: #e5edf7 !important; }
        .dark .text-slate-600,
        .dark .text-slate-500 { color: #9aa8bc !important; }
        .dark input,
        .dark select,
        .dark textarea {
            background-color: rgba(15, 23, 42, .86) !important;
            border-color: #334155 !important;
            color: #e5edf7 !important;
        }
        .dark table thead { background-color: rgba(30, 41, 59, .9) !important; }
        .theme-toggle {
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }
        .topbar {
            background:
                linear-gradient(90deg, rgba(15,118,110,.10), rgba(217,119,6,.08)),
                rgba(255,255,255,.84);
        }
        .dark .topbar {
            background:
                linear-gradient(90deg, rgba(45,212,191,.08), rgba(251,191,36,.06)),
                rgba(15,23,42,.88);
        }
        .content-surface {
            position: relative;
        }
        .content-surface > * {
            min-width: 0;
        }
        .content-surface::before {
            content: "";
            pointer-events: none;
            position: fixed;
            inset: 5rem 0 auto 18rem;
            height: 11rem;
            background: linear-gradient(90deg, rgba(15,118,110,.12), rgba(217,119,6,.10), transparent);
            opacity: .8;
            z-index: -1;
        }
    </style>
    <script>
        const savedTheme = localStorage.getItem('apart-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen text-slate-900">
<div class="flex min-h-screen">
    <aside class="shell-sidebar hidden w-72 border-r border-white/10 lg:block">
        <div class="flex h-20 items-center border-b border-white/10 px-6">
            <div class="brand-mark mr-3 grid h-11 w-11 place-items-center rounded-md text-cyan-100">
                <i class="fa-solid fa-hotel text-lg"></i>
            </div>
            <div>
                <div class="text-lg font-semibold tracking-wide">Apart Yönetim</div>
                <div class="text-xs text-cyan-100/75">Gece-gündüz operasyon paneli</div>
            </div>
        </div>
        <nav class="space-y-1 px-3 py-5 text-sm">
            @php
                $items = [
                    ['dashboard', 'dashboard', 'fa-chart-line', 'Dashboard'],
                    ['locations.index', 'locations.*', 'fa-location-dot', 'Lokasyonlar'],
                    ['rooms.index', 'rooms.*', 'fa-bed', 'Odalar'],
                    ['customers.index', 'customers.*', 'fa-users', 'Müşteriler'],
                    ['stays.index', 'stays.*', 'fa-right-to-bracket', 'Giriş/Çıkış'],
                    ['calendar', 'calendar', 'fa-calendar-days', 'Doluluk Takvimi'],
                    ['incomes.index', 'incomes.*', 'fa-sack-dollar', 'Gelirler'],
                    ['expenses.index', 'expenses.*', 'fa-file-invoice-dollar', 'Giderler'],
                    ['reports', 'reports', 'fa-chart-pie', 'Raporlar'],
                    ['subscriptions.index', 'subscriptions.*', 'fa-receipt', 'Abonelikler'],
                    ['maintenance.index', 'maintenance.*', 'fa-screwdriver-wrench', 'Temizlik/Bakım'],
                    ['account.edit', 'account.*', 'fa-user-gear', 'Hesap'],
                ];
            @endphp
            @foreach($items as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}" class="nav-item flex items-center gap-3 rounded-md px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-white text-slate-950 shadow-sm' : 'text-cyan-50/75 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid {{ $icon }} w-5 text-center"></i>{{ $label }}
                </a>
            @endforeach
        </nav>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="topbar sticky top-0 z-10 flex h-20 items-center justify-between border-b border-slate-200 px-4 backdrop-blur-xl md:px-8">
            <div>
                <h1 class="text-lg font-semibold md:text-xl">{{ $heading ?? 'Panel' }}</h1>
                <p class="text-xs text-slate-500">{{ $subheading ?? 'Operasyon, finans ve doluluk takibi' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                <button type="button" id="themeToggle" class="theme-toggle inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" title="Koyu modu değiştir">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i><span class="hidden sm:inline">Çıkış</span>
                    </button>
                </form>
            </div>
        </header>
        <nav class="sticky top-20 z-10 flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 text-sm lg:hidden">
            @foreach($items as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}" class="inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-teal-700 text-white' : 'bg-slate-100 text-slate-700' }}">
                    <i class="fa-solid {{ $icon }}"></i>{{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="content-surface px-4 py-6 md:px-8">
            @if(session('status'))
                <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif
            {{ $slot }}
        </div>
    </main>
</div>
<script>
    function refreshThemeIcon() {
        const icon = document.querySelector('#themeToggle i');
        if (!icon) return;
        icon.className = document.documentElement.classList.contains('dark') ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }
    window.addEventListener('load', () => {
        refreshThemeIcon();
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('apart-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            refreshThemeIcon();
        });
    });
</script>
</body>
</html>
