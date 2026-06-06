<x-layouts.app heading="Doluluk Takvimi" subheading="Oda ve lokasyon bazli tarih araligi gorunumu">
    @php
        $days = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) { $days->push($date->copy()); }
    @endphp
    <form method="get" class="mb-5 flex flex-wrap gap-3 rounded-md border border-slate-200 bg-white p-4">
        <input name="start" type="date" value="{{ $start->toDateString() }}" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <input name="end" type="date" value="{{ $end->toDateString() }}" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <button class="h-10 rounded-md bg-slate-900 px-4 text-sm font-semibold text-white">Goster</button>
    </form>
    <section class="overflow-auto rounded-md border border-slate-200 bg-white">
        <table class="min-w-full text-left text-xs">
            <thead class="bg-slate-100 text-slate-600"><tr><th class="sticky left-0 z-[1] bg-slate-100 p-3">Oda</th>@foreach($days as $day)<th class="whitespace-nowrap p-2 text-center">{{ $day->format('d.m') }}</th>@endforeach</tr></thead>
            <tbody>
            @foreach($rooms as $room)
                <tr class="border-t border-slate-100">
                    <td class="sticky left-0 bg-white p-3 text-sm"><strong>{{ $room->name }}</strong><div class="text-slate-500">{{ $room->location->name }}</div></td>
                    @foreach($days as $day)
                        @php($stay = $room->stays->first(fn ($s) => $s->check_in->lte($day) && $s->check_out->gte($day)))
                        <td class="p-1"><div title="{{ $stay?->customer?->full_name }}" class="h-7 min-w-8 rounded {{ $stay ? 'bg-cyan-500' : 'bg-emerald-100' }}"></div></td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
</x-layouts.app>
