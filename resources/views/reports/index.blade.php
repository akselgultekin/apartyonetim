<x-layouts.app heading="Kâr-Zarar Raporlari" subheading="Gelir - gider = net kâr mantigiyla tarih araligi analizi">
    @php($money = fn ($v) => number_format($v, 2, ',', '.').' TL')
    <form method="get" class="mb-5 flex flex-wrap items-end gap-3 rounded-md border border-slate-200 bg-white p-4">
        <x-input name="start" label="Baslangic" type="date" :value="$start->toDateString()" />
        <x-input name="end" label="Bitis" type="date" :value="$end->toDateString()" />
        <button class="h-10 rounded-md bg-slate-900 px-4 text-sm font-semibold text-white">Guncelle</button>
        <a class="inline-flex h-10 items-center rounded-md border border-slate-300 px-4 text-sm font-semibold" href="{{ route('exports.profit-loss', ['start' => $start->toDateString(), 'end' => $end->toDateString()]) }}">CSV indir</a>
    </form>
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat label="Toplam gelir" :value="$money($summary['income'])" tone="green" />
        <x-stat label="Toplam gider" :value="$money($summary['expense'])" tone="red" />
        <x-stat label="Net kâr/zarar" :value="$money($summary['net'])" :tone="$summary['net'] >= 0 ? 'green' : 'red'" />
    </div>
    <div class="mt-6 grid gap-5 xl:grid-cols-2">
        <section class="rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Lokasyon bazli</h2>
            <table class="w-full text-left text-sm"><thead class="text-slate-500"><tr><th class="py-2">Lokasyon</th><th>Gelir</th><th>Gider</th><th>Net</th></tr></thead><tbody>@foreach($locationRows as $row)<tr class="border-t"><td class="py-2">{{ $row['name'] }}</td><td>{{ $money($row['income']) }}</td><td>{{ $money($row['expense']) }}</td><td class="font-semibold">{{ $money($row['net']) }}</td></tr>@endforeach</tbody></table>
        </section>
        <section class="rounded-md border border-slate-200 bg-white p-5">
            <h2 class="mb-4 font-semibold">Oda bazli</h2>
            <table class="w-full text-left text-sm"><thead class="text-slate-500"><tr><th class="py-2">Oda</th><th>Gelir</th><th>Gider</th><th>Net</th></tr></thead><tbody>@foreach($roomRows as $row)<tr class="border-t"><td class="py-2">{{ $row['name'] }}</td><td>{{ $money($row['income']) }}</td><td>{{ $money($row['expense']) }}</td><td class="font-semibold">{{ $money($row['net']) }}</td></tr>@endforeach</tbody></table>
        </section>
        <section class="rounded-md border border-slate-200 bg-white p-5 xl:col-span-2">
            <h2 class="mb-4 font-semibold">Gider kategori dagilimi</h2>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">@foreach($categoryRows as $row)<div class="rounded-md bg-slate-50 p-3 text-sm"><div class="text-slate-500">{{ $row->category }}</div><strong>{{ $money($row->amount) }}</strong></div>@endforeach</div>
        </section>
    </div>
</x-layouts.app>
