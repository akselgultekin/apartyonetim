<x-layouts.app heading="Dashboard" subheading="Bugunku operasyon, gelir-gider ve yaklasan isler">
    @php
        $money = fn ($v) => number_format($v, 2, ',', '.').' TL';
        $periodLabels = ['daily' => 'Gunluk', 'weekly' => 'Haftalik', 'monthly' => 'Aylik', 'yearly' => 'Yillik'];
        $monthlyIncome = max($periods['monthly']['income'], 1);
        $monthlyExpense = max($periods['monthly']['expense'], 1);
        $monthlyScale = max($monthlyIncome, $monthlyExpense, 1);
    @endphp
    <section class="mb-6 overflow-hidden rounded-md border border-slate-200 bg-slate-950 text-white shadow-sm">
        <div class="grid gap-6 p-5 md:grid-cols-[1.1fr_.9fr] md:p-6">
            <div>
                <div class="inline-flex items-center gap-2 rounded-md bg-white/10 px-3 py-1 text-xs font-medium text-cyan-100">
                    <i data-lucide="Sparkles" class="h-4 w-4"></i> Canli operasyon ozeti
                </div>
                <h2 class="mt-4 max-w-2xl text-2xl font-semibold tracking-tight md:text-3xl">Bugunku konaklama akisi ve finans nabzi tek ekranda.</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Gece vardiyasi, muhasebe ve yonetim ayni panelde doluluk, cikis, tahsilat ve bekleyen isleri hizli okuyabilir.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-md border border-white/10 bg-white/10 p-4">
                    <div class="text-xs text-slate-300">Aylik gelir</div>
                    <div class="mt-2 text-xl font-semibold">{{ $money($periods['monthly']['income']) }}</div>
                    <div class="mt-3 h-2 rounded-full bg-white/10"><div class="h-2 rounded-full bg-teal-400" style="width: {{ min(100, round(($monthlyIncome / $monthlyScale) * 100)) }}%"></div></div>
                </div>
                <div class="rounded-md border border-white/10 bg-white/10 p-4">
                    <div class="text-xs text-slate-300">Aylik gider</div>
                    <div class="mt-2 text-xl font-semibold">{{ $money($periods['monthly']['expense']) }}</div>
                    <div class="mt-3 h-2 rounded-full bg-white/10"><div class="h-2 rounded-full bg-amber-400" style="width: {{ min(100, round(($monthlyExpense / $monthlyScale) * 100)) }}%"></div></div>
                </div>
                <div class="rounded-md border border-white/10 bg-white/10 p-4 sm:col-span-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-300">Aylik net kâr/zarar</span>
                        <strong class="{{ $periods['monthly']['net'] >= 0 ? 'text-teal-200' : 'text-rose-200' }}">{{ $money($periods['monthly']['net']) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-stat label="Bugunku dolu oda" :value="$occupiedRooms" tone="blue" icon="BedDouble" />
        <x-stat label="Bugunku bos oda" :value="$emptyRooms" tone="green" icon="DoorOpen" />
        <x-stat label="Bugunku giris" :value="$todayCheckins->count()" icon="LogIn" />
        <x-stat label="Bugunku cikis" :value="$todayCheckouts->count()" tone="amber" icon="LogOut" />
        <x-stat label="Odenmemis kira" :value="$money($unpaidRent)" tone="red" icon="Receipt" />
        @foreach($periods as $key => $row)
            <x-stat :label="$periodLabels[$key].' net kâr/zarar'" :value="$money($row['net'])" :hint="'Gelir '.$money($row['income']).' / Gider '.$money($row['expense'])" :tone="$row['net'] >= 0 ? 'green' : 'red'" icon="ChartNoAxesCombined" />
        @endforeach
    </div>

    <div class="mt-6 grid gap-5 xl:grid-cols-[1.1fr_.9fr]">
        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold">Lokasyon bazli doluluk</h2>
                <a href="{{ route('calendar') }}" class="text-sm font-medium text-slate-700 hover:text-slate-950">Takvim</a>
            </div>
            <div class="space-y-4">
                @foreach($locationOccupancy as $row)
                    <div>
                        <div class="mb-1 flex justify-between text-sm"><span>{{ $row['name'] }}</span><span>{{ $row['occupied'] }}/{{ $row['total'] }} - {{ $row['rate'] }}%</span></div>
                        <div class="h-2 rounded-full bg-slate-100"><div class="h-2 rounded-full bg-teal-500" style="width: {{ $row['rate'] }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold">Dönemsel finans</h2>
                <a href="{{ route('reports') }}" class="text-sm font-medium text-slate-700 hover:text-slate-950">Raporlar</a>
            </div>
            <div class="overflow-hidden rounded-md border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-600">
                    <tr><th class="p-3">Donem</th><th class="p-3">Gelir</th><th class="p-3">Gider</th><th class="p-3">Net</th></tr>
                    </thead>
                    <tbody>
                    @foreach($periods as $key => $row)
                        <tr class="border-t border-slate-100">
                            <td class="p-3 font-medium">{{ $periodLabels[$key] }}</td>
                            <td class="p-3 text-teal-700">{{ $money($row['income']) }}</td>
                            <td class="p-3 text-amber-700">{{ $money($row['expense']) }}</td>
                            <td class="p-3 font-semibold {{ $row['net'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $money($row['net']) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-5 xl:grid-cols-[.85fr_1.15fr]">
        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">En cok gelir getiren odalar</h2>
            <div class="space-y-3">
                @forelse($topRooms as $room)
                    @php($revenue = (float) ($room->revenue ?? 0))
                    <div class="rounded-md bg-slate-50 px-3 py-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium">{{ $room->name }}</span>
                            <strong>{{ $money($revenue) }}</strong>
                        </div>
                        <div class="mt-2 h-1.5 rounded-full bg-slate-200">
                            <div class="h-1.5 rounded-full bg-cyan-500" style="width: {{ min(100, round(($revenue / max((float) ($topRooms->max('revenue') ?? 1), 1)) * 100)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Gelir kaydi yok.</p>
                @endforelse
            </div>
        </section>

        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Bugunku giris/cikis akisi</h2>
            <div class="grid gap-3 md:grid-cols-2">
                <div class="rounded-md bg-slate-50 p-4">
                    <div class="mb-3 text-sm font-semibold text-slate-700">Girisler</div>
                    @forelse($todayCheckins as $stay)
                        <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $stay->customer->full_name }}</strong><div class="text-slate-500">{{ $stay->room->name }}</div></div>
                    @empty
                        <p class="text-sm text-slate-500">Bugun giris yok.</p>
                    @endforelse
                </div>
                <div class="rounded-md bg-slate-50 p-4">
                    <div class="mb-3 text-sm font-semibold text-slate-700">Cikislar</div>
                    @forelse($todayCheckouts as $stay)
                        <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $stay->customer->full_name }}</strong><div class="text-slate-500">{{ $stay->room->name }}</div></div>
                    @empty
                        <p class="text-sm text-slate-500">Bugun cikis yok.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-5 xl:grid-cols-3">
        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Yaklasan odemeler</h2>
            @forelse($upcomingPayments as $item)
                <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $item->subscriber_number }}</strong><div class="text-slate-500">{{ $item->due_date->format('d.m.Y') }} - {{ $money($item->bill_amount) }}</div></div>
            @empty
                <p class="text-sm text-slate-500">Kayit yok.</p>
            @endforelse
        </section>
        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Yaklasan cikislar</h2>
            @forelse($upcomingCheckouts as $stay)
                <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $stay->customer->full_name }}</strong><div class="text-slate-500">{{ $stay->room->name }} - {{ $stay->check_out->format('d.m.Y') }}</div></div>
            @empty
                <p class="text-sm text-slate-500">Kayit yok.</p>
            @endforelse
        </section>
        <section class="shell-card rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Son hareketler</h2>
            @forelse($activities as $activity)
                <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $activity->title }}</strong><div class="text-slate-500">{{ $activity->body }} - {{ $activity->created_at->diffForHumans() }}</div></div>
            @empty
                <p class="text-sm text-slate-500">Kayit yok.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
