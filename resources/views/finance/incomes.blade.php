<x-layouts.app heading="Gelirler" subheading="Kira, depozito, hizmet ve diger gelir kayitlari">
    @php($money = fn ($v) => number_format($v, 2, ',', '.').' TL')
    <x-filter-bar>
        <input name="q" value="{{ request('q') }}" placeholder="Gelir ara" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <input name="start" value="{{ request('start') }}" type="date" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <input name="end" value="{{ request('end') }}" type="date" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <select name="payment_status" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Odeme</option>@foreach(['paid'=>'Odendi','partial'=>'Kismi','unpaid'=>'Odenmedi'] as $k=>$v)<option value="{{ $k }}" @selected(request('payment_status')===$k)>{{ $v }}</option>@endforeach</select>
    </x-filter-bar>
    <div class="grid gap-5 xl:grid-cols-[.8fr_1.2fr]">
        <form method="post" action="{{ $editing ? route('incomes.update', $editing) : route('incomes.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">{{ $editing ? 'Gelir duzenle' : 'Yeni gelir' }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <x-input name="title" label="Baslik" :value="$editing?->title" />
                <x-select name="type" label="Tur">@foreach(['daily_rent'=>'Gunluk kira','weekly_rent'=>'Haftalik kira','monthly_rent'=>'Aylik kira','yearly_rent'=>'Yillik kira','deposit'=>'Depozito','service'=>'Ek hizmet','other'=>'Diger'] as $k=>$v)<option value="{{ $k }}" @selected(old('type', $editing?->type ?? 'daily_rent')===$k)>{{ $v }}</option>@endforeach</x-select>
                <x-input name="amount" label="Tutar" type="number" step="0.01" :value="$editing?->amount ?? 0" />
                <x-input name="paid_amount" label="Odenen" type="number" step="0.01" :value="$editing?->paid_amount ?? 0" />
                <x-input name="date" label="Tarih" type="date" :value="$editing?->date?->toDateString() ?? now()->toDateString()" />
                <x-select name="payment_method" label="Yontem">@foreach(['cash'=>'Nakit','bank_transfer'=>'Havale/EFT','credit_card'=>'Kredi karti','other'=>'Diger'] as $k=>$v)<option value="{{ $k }}" @selected(old('payment_method', $editing?->payment_method ?? 'cash')===$k)>{{ $v }}</option>@endforeach</x-select>
                <x-select name="location_id" label="Lokasyon"><option value="">Secilmedi</option>@foreach($locations as $location)<option value="{{ $location->id }}" @selected(old('location_id', $editing?->location_id)==$location->id)>{{ $location->name }}</option>@endforeach</x-select>
                <x-select name="room_id" label="Oda"><option value="">Secilmedi</option>@foreach($rooms as $room)<option value="{{ $room->id }}" @selected(old('room_id', $editing?->room_id)==$room->id)>{{ $room->name }}</option>@endforeach</x-select>
                <x-select name="customer_id" label="Musteri"><option value="">Secilmedi</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id', $editing?->customer_id)==$customer->id)>{{ $customer->full_name }}</option>@endforeach</x-select>
                <div class="md:col-span-2"><x-textarea name="notes" label="Not" :value="$editing?->notes" /></div>
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white md:col-span-2">Kaydet</button>
            </div>
        </form>
        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Gelir</th><th class="p-3">Iliski</th><th class="p-3">Tarih</th><th class="p-3">Odeme</th><th class="p-3"></th></tr></thead><tbody>@foreach($incomes as $income)<tr class="border-t border-slate-100"><td class="p-3"><strong>{{ $income->title }}</strong><div class="text-slate-500">{{ $income->type }}</div></td><td class="p-3">{{ $income->location?->name }}<div class="text-slate-500">{{ $income->room?->name }} {{ $income->customer?->full_name }}</div></td><td class="p-3">{{ $income->date->format('d.m.Y') }}</td><td class="p-3">{{ $money($income->paid_amount) }}<div class="text-slate-500">{{ $income->payment_status }}</div></td><td class="p-3 text-right"><a class="font-medium text-slate-700" href="{{ route('incomes.index', ['edit' => $income->id]) }}">Duzenle</a></td></tr>@endforeach</tbody></table>
            <div class="p-3">{{ $incomes->links() }}</div>
        </section>
    </div>
</x-layouts.app>
