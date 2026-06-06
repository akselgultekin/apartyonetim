<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Apart Yonetim Paneli' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <style>
        [x-cloak] { display: none; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="flex min-h-screen">
    <aside class="hidden w-72 border-r border-slate-200 bg-white lg:block">
        <div class="flex h-16 items-center border-b border-slate-200 px-6">
            <div>
                <div class="text-lg font-semibold">Apart Yonetim</div>
                <div class="text-xs text-slate-500">Kapali devre isletme paneli</div>
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
                <a href="{{ route($route) }}" class="flex items-center gap-3 rounded-md px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">
                    <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>{{ $label }}
                </a>
            @endforeach
        </nav>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-10 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur md:px-8">
            <div>
                <h1 class="text-lg font-semibold md:text-xl">{{ $heading ?? 'Panel' }}</h1>
                <p class="text-xs text-slate-500">{{ $subheading ?? 'Operasyon, finans ve doluluk takibi' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i data-lucide="LogOut" class="h-4 w-4"></i><span class="hidden sm:inline">Cikis</span>
                    </button>
                </form>
            </div>
        </header>
        <nav class="sticky top-16 z-10 flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 text-sm lg:hidden">
            @foreach($items as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}" class="inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
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
    window.addEventListener('load', () => window.lucide?.createIcons());
</script>
</body>
</html>
