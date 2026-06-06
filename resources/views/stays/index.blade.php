<x-layouts.app heading="Giris / Cikis" subheading="Tarih araligi cakismasini engelleyen konaklama kayitlari">
    <x-filter-bar>
        <select name="room_id" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum odalar</option>@foreach($rooms as $room)<option value="{{ $room->id }}" @selected(request('room_id')==$room->id)>{{ $room->name }}</option>@endforeach</select>
        <select name="customer_id" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum musteriler</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" @selected(request('customer_id')==$customer->id)>{{ $customer->full_name }}</option>@endforeach</select>
        <input name="start" value="{{ request('start') }}" type="date" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <input name="end" value="{{ request('end') }}" type="date" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
    </x-filter-bar>
    <div class="grid gap-5 xl:grid-cols-[.85fr_1.15fr]">
        <form method="post" action="{{ route('stays.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">Yeni konaklama</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <x-select name="customer_id" label="Musteri">@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->full_name }}</option>@endforeach</x-select>
                <x-select name="room_id" label="Oda">@foreach($rooms as $room)<option value="{{ $room->id }}">{{ $room->location->name }} / {{ $room->name }}</option>@endforeach</x-select>
                <x-input name="check_in" label="Giris tarihi" type="date" :value="now()->toDateString()" />
                <x-input name="check_out" label="Cikis tarihi" type="date" :value="now()->addDay()->toDateString()" />
                <x-select name="rental_type" label="Kiralama turu">@foreach(['daily'=>'Gunluk','weekly'=>'Haftalik','monthly'=>'Aylik','yearly'=>'Yillik'] as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</x-select>
                <x-input name="total_rent" label="Toplam kira" type="number" step="0.01" value="0" />
                <x-input name="paid_amount" label="Odenen tutar" type="number" step="0.01" value="0" />
                <div class="md:col-span-2"><x-textarea name="notes" label="Not" /></div>
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white md:col-span-2">Kaydet</button>
            </div>
        </form>
        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Musteri</th><th class="p-3">Oda</th><th class="p-3">Tarih</th><th class="p-3">Odeme</th><th class="p-3"></th></tr></thead>
                <tbody>@foreach($stays as $stay)<tr class="border-t border-slate-100"><td class="p-3"><strong>{{ $stay->customer->full_name }}</strong><div class="text-slate-500">{{ $stay->rental_type }}</div></td><td class="p-3">{{ $stay->room->name }}<div class="text-slate-500">{{ $stay->room->location->name }}</div></td><td class="p-3">{{ $stay->check_in->format('d.m.Y') }}<br>{{ $stay->check_out->format('d.m.Y') }}</td><td class="p-3">{{ number_format($stay->paid_amount, 2, ',', '.') }} / {{ number_format($stay->total_rent, 2, ',', '.') }} TL<div class="text-slate-500">{{ $stay->payment_status }}</div></td><td class="p-3 text-right">@if(!$stay->checked_out_at)<form method="post" action="{{ route('stays.checkout', $stay) }}">@csrf<button class="rounded-md border px-3 py-1 font-medium">Cikis</button></form>@else<span class="text-slate-500">Tamam</span>@endif</td></tr>@endforeach</tbody>
            </table>
            <div class="p-3">{{ $stays->links() }}</div>
        </section>
    </div>
</x-layouts.app>
