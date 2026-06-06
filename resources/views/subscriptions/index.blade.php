<x-layouts.app heading="Abonelikler" subheading="Sayaç, son odeme ve fatura takibi">
    <x-filter-bar>
        <select name="location_id" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum lokasyonlar</option>@foreach($locations as $location)<option value="{{ $location->id }}" @selected(request('location_id')==$location->id)>{{ $location->name }}</option>@endforeach</select>
        <select name="room_id" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum odalar</option>@foreach($rooms as $room)<option value="{{ $room->id }}" @selected(request('room_id')==$room->id)>{{ $room->name }}</option>@endforeach</select>
        <select name="payment_status" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Odeme</option><option value="paid" @selected(request('payment_status')==='paid')>Odendi</option><option value="unpaid" @selected(request('payment_status')==='unpaid')>Odenmedi</option></select>
    </x-filter-bar>
    <div class="grid gap-5 xl:grid-cols-[.8fr_1.2fr]">
        <form method="post" action="{{ route('subscriptions.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">Yeni abonelik/fatura</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <x-select name="utility_type" label="Tur">@foreach(['water'=>'Su','electricity'=>'Elektrik','gas'=>'Dogalgaz','internet'=>'Internet','other'=>'Diger'] as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</x-select>
                <x-input name="subscriber_number" label="Abone no" />
                <x-input name="company" label="Firma" />
                <x-input name="due_date" label="Son odeme" type="date" :value="now()->addWeek()->toDateString()" />
                <x-input name="bill_amount" label="Fatura tutari" type="number" step="0.01" value="0" />
                <x-select name="payment_status" label="Odeme"><option value="unpaid">Odenmedi</option><option value="paid">Odendi</option></x-select>
                <x-select name="location_id" label="Lokasyon"><option value="">Secilmedi</option>@foreach($locations as $location)<option value="{{ $location->id }}">{{ $location->name }}</option>@endforeach</x-select>
                <x-select name="room_id" label="Oda"><option value="">Secilmedi</option>@foreach($rooms as $room)<option value="{{ $room->id }}">{{ $room->name }}</option>@endforeach</x-select>
                <div class="md:col-span-2"><x-textarea name="notes" label="Not" /></div>
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white md:col-span-2">Kaydet</button>
            </div>
        </form>
        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Abonelik</th><th class="p-3">Iliski</th><th class="p-3">Son odeme</th><th class="p-3">Tutar</th></tr></thead><tbody>@foreach($subscriptions as $subscription)<tr class="border-t border-slate-100"><td class="p-3"><strong>{{ $subscription->subscriber_number }}</strong><div class="text-slate-500">{{ $subscription->utility_type }} {{ $subscription->company }}</div></td><td class="p-3">{{ $subscription->location?->name }}<div class="text-slate-500">{{ $subscription->room?->name }}</div></td><td class="p-3">{{ $subscription->due_date->format('d.m.Y') }}</td><td class="p-3">{{ number_format($subscription->bill_amount, 2, ',', '.') }} TL<div class="text-slate-500">{{ $subscription->payment_status }}</div></td></tr>@endforeach</tbody></table>
            <div class="p-3">{{ $subscriptions->links() }}</div>
        </section>
    </div>
</x-layouts.app>
