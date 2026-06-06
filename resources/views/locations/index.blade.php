<x-layouts.app heading="Lokasyonlar" subheading="Apart/adres bazlı operasyon ve finans ayrımı">
    <x-filter-bar>
        <input name="q" value="{{ request('q') }}" placeholder="Lokasyon ara" class="h-10 rounded-md border border-slate-300 px-3 text-sm md:col-span-2">
        <select name="status" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
            <option value="">Tüm durumlar</option>
            <option value="active" @selected(request('status')==='active')>Aktif</option>
            <option value="passive" @selected(request('status')==='passive')>Pasif</option>
        </select>
    </x-filter-bar>

    <div class="grid gap-5 xl:grid-cols-[.8fr_1.2fr]">
        <form method="post" action="{{ $editing ? route('locations.update', $editing) : route('locations.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">{{ $editing ? 'Lokasyon düzenle' : 'Yeni lokasyon' }}</h2>
            <div class="grid gap-4">
                <x-input name="name" label="Ad" :value="$editing?->name" />
                <x-select name="is_active" label="Durum" :value="$editing?->is_active">
                    <option value="1" @selected(old('is_active', $editing?->is_active ?? 1)==1)>Aktif</option>
                    <option value="0" @selected(old('is_active', $editing?->is_active)==0)>Pasif</option>
                </x-select>
                <x-textarea name="address" label="Adres" :value="$editing?->address" />
                <x-textarea name="description" label="Açıklama" :value="$editing?->description" />
                <x-textarea name="notes" label="Notlar" :value="$editing?->notes" />
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white">Kaydet</button>
            </div>
        </form>

        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Lokasyon</th><th class="p-3">Oda</th><th class="p-3">Durum</th><th class="p-3"></th></tr></thead>
                <tbody>
                @foreach($locations as $location)
                    <tr class="border-t border-slate-100">
                        <td class="p-3"><strong>{{ $location->name }}</strong><div class="text-slate-500">{{ $location->address }}</div></td>
                        <td class="p-3">{{ $location->rooms_count }}</td>
                        <td class="p-3">{{ $location->is_active ? 'Aktif' : 'Pasif' }}</td>
                        <td class="p-3 text-right"><a class="font-medium text-slate-700" href="{{ route('locations.index', ['edit' => $location->id]) }}">Düzenle</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="p-3">{{ $locations->links() }}</div>
        </section>
    </div>
</x-layouts.app>
