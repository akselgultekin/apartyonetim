<!doctype html>
<html lang="tr" class="theme-ready">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Apart Yonetim Paneli' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
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
                radial-gradient(circle at 10% 0%, rgba(15, 118, 110, .12), transparent 30rem),
                radial-gradient(circle at 92% 8%, rgba(217, 119, 6, .12), transparent 24rem),
                var(--page-bg);
            color: var(--ink);
        }
        .shell-sidebar {
            background:
                linear-gradient(180deg, #10233f 0%, #0f3d3e 58%, #182131 100%);
            color: #ecfeff;
        }
        .shell-card {
            background: var(--panel-bg);
            border-color: var(--panel-border);
            box-shadow: 0 18px 50px rgba(15, 23, 42, .06);
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
            <div class="mr-3 grid h-11 w-11 place-items-center rounded-md bg-white/12 text-cyan-100">
                <i data-lucide="Hotel" class="h-6 w-6"></i>
            </div>
            <div>
                <div class="text-lg font-semibold tracking-wide">Apart Yonetim</div>
                <div class="text-xs text-cyan-100/75">Gece-gunduz operasyon paneli</div>
            </div>
        </div>
        <nav class="space-y-1 px-3 py-5 text-sm">
            @php
                $items = [
                    ['dashboard', 'dashboard', 'Gauge', 'Dashboard'],
                    ['locations.index', 'locations.*', 'MapPin', 'Lokasyonlar'],
                    ['rooms.index', 'rooms.*', 'DoorOpen', 'Odalar'],
                    ['customers.index', 'customers.*', 'Users', 'Musteriler'],
                    ['stays.index', 'stays.*', 'CalendarCheck', 'Giris/Cikis'],
                    ['calendar', 'calendar', 'CalendarDays', 'Doluluk Takvimi'],
                    ['incomes.index', 'incomes.*', 'TrendingUp', 'Gelirler'],
                    ['expenses.index', 'expenses.*', 'TrendingDown', 'Giderler'],
                    ['reports', 'reports', 'BarChart3', 'Raporlar'],
                    ['subscriptions.index', 'subscriptions.*', 'ReceiptText', 'Abonelikler'],
                    ['maintenance.index', 'maintenance.*', 'Wrench', 'Temizlik/Bakim'],
                    ['account.edit', 'account.*', 'UserCog', 'Hesap'],
                ];
            @endphp
            @foreach($items as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}" class="flex items-center gap-3 rounded-md px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-white text-slate-950 shadow-sm' : 'text-cyan-50/75 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>{{ $label }}
                </a>
            @endforeach
        </nav>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-10 flex h-20 items-center justify-between border-b border-slate-200 bg-white/82 px-4 backdrop-blur-xl md:px-8">
            <div>
                <h1 class="text-lg font-semibold md:text-xl">{{ $heading ?? 'Panel' }}</h1>
                <p class="text-xs text-slate-500">{{ $subheading ?? 'Operasyon, finans ve doluluk takibi' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                <button type="button" id="themeToggle" class="theme-toggle inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" title="Koyu modu degistir">
                    <i data-lucide="Moon" class="h-4 w-4"></i>
                </button>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i data-lucide="LogOut" class="h-4 w-4"></i><span class="hidden sm:inline">Cikis</span>
                    </button>
                </form>
            </div>
        </header>
        <nav class="sticky top-20 z-10 flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 text-sm lg:hidden">
            @foreach($items as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}" class="inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-teal-700 text-white' : 'bg-slate-100 text-slate-700' }}">
                    <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>{{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-6 md:px-8">
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
        icon.setAttribute('data-lucide', document.documentElement.classList.contains('dark') ? 'Sun' : 'Moon');
        window.lucide?.createIcons();
    }
    window.addEventListener('load', () => {
        window.lucide?.createIcons();
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
