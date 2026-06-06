<x-layouts.app heading="Odalar ve Daireler" subheading="Fiyat, kapasite, durum, temizlik ve bakim takibi">
    <x-filter-bar>
        <input name="q" value="{{ request('q') }}" placeholder="Oda ara" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
        <select name="location_id" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum lokasyonlar</option>@foreach($locations as $location)<option value="{{ $location->id }}" @selected(request('location_id')==$location->id)>{{ $location->name }}</option>@endforeach</select>
        <select name="status" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum durumlar</option>@foreach(['available'=>'Bos','occupied'=>'Dolu','reserved'=>'Rezerve','maintenance'=>'Bakimda','passive'=>'Pasif'] as $k=>$v)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>@endforeach</select>
    </x-filter-bar>

    <div class="grid gap-5 xl:grid-cols-[.9fr_1.1fr]">
        <form method="post" action="{{ $editing ? route('rooms.update', $editing) : route('rooms.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">{{ $editing ? 'Oda duzenle' : 'Yeni oda/daire' }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <x-select name="location_id" label="Lokasyon">@foreach($locations as $location)<option value="{{ $location->id }}" @selected(old('location_id', $editing?->location_id)==$location->id)>{{ $location->name }}</option>@endforeach</x-select>
                <x-input name="name" label="Oda/daire adi" :value="$editing?->name" />
                <x-input name="type" label="Tip" :value="$editing?->type ?? 'Standart'" />
                <x-input name="capacity" label="Kapasite" type="number" :value="$editing?->capacity ?? 1" />
                <x-input name="daily_price" label="Gunluk fiyat" type="number" step="0.01" :value="$editing?->daily_price ?? 0" />
                <x-input name="weekly_price" label="Haftalik fiyat" type="number" step="0.01" :value="$editing?->weekly_price ?? 0" />
                <x-input name="monthly_price" label="Aylik fiyat" type="number" step="0.01" :value="$editing?->monthly_price ?? 0" />
                <x-input name="yearly_price" label="Yillik fiyat" type="number" step="0.01" :value="$editing?->yearly_price ?? 0" />
                <x-input name="deposit" label="Depozito" type="number" step="0.01" :value="$editing?->deposit ?? 0" />
                <x-select name="status" label="Oda durumu">@foreach(['available'=>'Bos','occupied'=>'Dolu','reserved'=>'Rezerve','maintenance'=>'Bakimda','passive'=>'Pasif'] as $k=>$v)<option value="{{ $k }}" @selected(old('status', $editing?->status ?? 'available')===$k)>{{ $v }}</option>@endforeach</x-select>
                <x-select name="cleaning_status" label="Temizlik">@foreach(['clean'=>'Temiz','dirty'=>'Kirli','waiting'=>'Temizlik bekliyor'] as $k=>$v)<option value="{{ $k }}" @selected(old('cleaning_status', $editing?->cleaning_status ?? 'clean')===$k)>{{ $v }}</option>@endforeach</x-select>
                <x-select name="maintenance_status" label="Bakim">@foreach(['normal'=>'Normal','maintenance'=>'Bakimda','faulty'=>'Arizali'] as $k=>$v)<option value="{{ $k }}" @selected(old('maintenance_status', $editing?->maintenance_status ?? 'normal')===$k)>{{ $v }}</option>@endforeach</x-select>
                <div class="md:col-span-2"><x-textarea name="notes" label="Notlar" :value="$editing?->notes" /></div>
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white md:col-span-2">Kaydet</button>
            </div>
        </form>

        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Oda</th><th class="p-3">Lokasyon</th><th class="p-3">Durum</th><th class="p-3">Aylik</th><th class="p-3"></th></tr></thead>
                <tbody>
                @foreach($rooms as $room)
                    <tr class="border-t border-slate-100">
                        <td class="p-3"><strong>{{ $room->name }}</strong><div class="text-slate-500">{{ $room->type }} / {{ $room->capacity }} kisi</div></td>
                        <td class="p-3">{{ $room->location->name }}</td>
                        <td class="p-3">{{ $room->status }}<div class="text-slate-500">{{ $room->cleaning_status }} / {{ $room->maintenance_status }}</div></td>
                        <td class="p-3">{{ number_format($room->monthly_price, 2, ',', '.') }} TL</td>
                        <td class="p-3 text-right"><a class="font-medium text-slate-700" href="{{ route('rooms.index', ['edit' => $room->id]) }}">Duzenle</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="p-3">{{ $rooms->links() }}</div>
        </section>
    </div>
</x-layouts.app>
