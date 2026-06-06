<x-layouts.app heading="Dashboard" subheading="Bugunku operasyon, gelir-gider ve yaklasan isler">
    @php
        $money = fn ($v) => number_format($v, 2, ',', '.').' TL';
        $periodLabels = ['daily' => 'Gunluk', 'weekly' => 'Haftalik', 'monthly' => 'Aylik', 'yearly' => 'Yillik'];
    @endphp
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-stat label="Bugunku dolu oda" :value="$occupiedRooms" tone="blue" />
        <x-stat label="Bugunku bos oda" :value="$emptyRooms" tone="green" />
        <x-stat label="Bugunku giris" :value="$todayCheckins->count()" />
        <x-stat label="Bugunku cikis" :value="$todayCheckouts->count()" tone="amber" />
        <x-stat label="Odenmemis kira" :value="$money($unpaidRent)" tone="red" />
        @foreach($periods as $key => $row)
            <x-stat :label="$periodLabels[$key].' net kâr/zarar'" :value="$money($row['net'])" :hint="'Gelir '.$money($row['income']).' / Gider '.$money($row['expense'])" :tone="$row['net'] >= 0 ? 'green' : 'red'" />
        @endforeach
    </div>

    <div class="mt-6 grid gap-5 xl:grid-cols-[1.2fr_.8fr]">
        <section class="rounded-md border border-slate-200 bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold">Lokasyon bazli doluluk</h2>
                <a href="{{ route('calendar') }}" class="text-sm font-medium text-slate-700 hover:text-slate-950">Takvim</a>
            </div>
            <div class="space-y-4">
                @foreach($locationOccupancy as $row)
                    <div>
                        <div class="mb-1 flex justify-between text-sm"><span>{{ $row['name'] }}</span><span>{{ $row['occupied'] }}/{{ $row['total'] }} - {{ $row['rate'] }}%</span></div>
                        <div class="h-2 rounded-full bg-slate-100"><div class="h-2 rounded-full bg-cyan-500" style="width: {{ $row['rate'] }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">En cok gelir getiren odalar</h2>
            <div class="space-y-3">
                @foreach($topRooms as $room)
                    <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2 text-sm">
                        <span>{{ $room->name }}</span><strong>{{ $money($room->revenue) }}</strong>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-5 xl:grid-cols-3">
        <section class="rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Yaklasan odemeler</h2>
            @forelse($upcomingPayments as $item)
                <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $item->subscriber_number }}</strong><div class="text-slate-500">{{ $item->due_date->format('d.m.Y') }} - {{ $money($item->bill_amount) }}</div></div>
            @empty
                <p class="text-sm text-slate-500">Kayit yok.</p>
            @endforelse
        </section>
        <section class="rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Yaklasan cikislar</h2>
            @forelse($upcomingCheckouts as $stay)
                <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $stay->customer->full_name }}</strong><div class="text-slate-500">{{ $stay->room->name }} - {{ $stay->check_out->format('d.m.Y') }}</div></div>
            @empty
                <p class="text-sm text-slate-500">Kayit yok.</p>
            @endforelse
        </section>
        <section class="rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Son hareketler</h2>
            @forelse($activities as $activity)
                <div class="border-b border-slate-100 py-2 text-sm last:border-0"><strong>{{ $activity->title }}</strong><div class="text-slate-500">{{ $activity->body }} - {{ $activity->created_at->diffForHumans() }}</div></div>
            @empty
                <p class="text-sm text-slate-500">Kayit yok.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
