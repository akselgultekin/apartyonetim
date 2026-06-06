<x-layouts.app heading="Giderler" subheading="Fatura, personel, bakim, vergi ve operasyon giderleri">
    @php($money = fn ($v) => number_format($v, 2, ',', '.').' TL')
    <x-filter-bar>
        <input name="q" value="{{ request('q') }}" placeholder="Gider ara" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <input name="start" value="{{ request('start') }}" type="date" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <input name="end" value="{{ request('end') }}" type="date" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <select name="payment_status" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Odeme</option><option value="paid" @selected(request('payment_status')==='paid')>Odendi</option><option value="unpaid" @selected(request('payment_status')==='unpaid')>Odenmedi</option></select>
    </x-filter-bar>
    <div class="grid gap-5 xl:grid-cols-[.8fr_1.2fr]">
        <form method="post" action="{{ route('expenses.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">Yeni gider</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <x-input name="title" label="Baslik" />
                <x-select name="category" label="Kategori">@foreach(['personnel'=>'Personel','water'=>'Su','electricity'=>'Elektrik','gas'=>'Dogalgaz','internet'=>'Internet','dues'=>'Aidat','cleaning'=>'Temizlik','maintenance'=>'Bakim/onarim','inventory'=>'Demirbas','tax'=>'Vergi','rent'=>'Kira','other'=>'Diger'] as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</x-select>
                <x-input name="amount" label="Tutar" type="number" step="0.01" value="0" />
                <x-input name="date" label="Tarih" type="date" :value="now()->toDateString()" />
                <x-select name="payment_status" label="Odeme"><option value="paid">Odendi</option><option value="unpaid">Odenmedi</option></x-select>
                <x-select name="location_id" label="Lokasyon"><option value="">Secilmedi</option>@foreach($locations as $location)<option value="{{ $location->id }}">{{ $location->name }}</option>@endforeach</x-select>
                <x-select name="room_id" label="Oda"><option value="">Secilmedi</option>@foreach($rooms as $room)<option value="{{ $room->id }}">{{ $room->name }}</option>@endforeach</x-select>
                <div class="md:col-span-2"><x-textarea name="notes" label="Not" /></div>
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white md:col-span-2">Kaydet</button>
            </div>
        </form>
        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Gider</th><th class="p-3">Iliski</th><th class="p-3">Tarih</th><th class="p-3">Tutar</th></tr></thead><tbody>@foreach($expenses as $expense)<tr class="border-t border-slate-100"><td class="p-3"><strong>{{ $expense->title }}</strong><div class="text-slate-500">{{ $expense->category }}</div></td><td class="p-3">{{ $expense->location?->name }}<div class="text-slate-500">{{ $expense->room?->name }}</div></td><td class="p-3">{{ $expense->date->format('d.m.Y') }}</td><td class="p-3">{{ $money($expense->amount) }}<div class="text-slate-500">{{ $expense->payment_status }}</div></td></tr>@endforeach</tbody></table>
            <div class="p-3">{{ $expenses->links() }}</div>
        </section>
    </div>
</x-layouts.app>
