<x-layouts.app heading="Temizlik / Bakim" subheading="Oda temizlik durumu, ariza ve bakim kayitlari">
    <x-filter-bar>
        <select name="room_id" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tum odalar</option>@foreach($rooms as $room)<option value="{{ $room->id }}" @selected(request('room_id')==$room->id)>{{ $room->name }}</option>@endforeach</select>
        <select name="type" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Tur</option>@foreach(['cleaning'=>'Temizlik','repair'=>'Ariza/bakim','inspection'=>'Kontrol'] as $k=>$v)<option value="{{ $k }}" @selected(request('type')===$k)>{{ $v }}</option>@endforeach</select>
        <select name="status" class="h-10 rounded-md border border-slate-300 px-3 text-sm"><option value="">Durum</option>@foreach(['open'=>'Acik','in_progress'=>'Islemde','done'=>'Tamam'] as $k=>$v)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>@endforeach</select>
    </x-filter-bar>
    <div class="grid gap-5 xl:grid-cols-[.8fr_1.2fr]">
        <form method="post" action="{{ route('maintenance.store') }}" class="rounded-md border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="mb-4 font-semibold">Yeni kayit</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <x-select name="room_id" label="Oda">@foreach($rooms as $room)<option value="{{ $room->id }}">{{ $room->location->name }} / {{ $room->name }}</option>@endforeach</x-select>
                <x-select name="type" label="Tur"><option value="cleaning">Temizlik</option><option value="repair">Ariza/bakim</option><option value="inspection">Kontrol</option></x-select>
                <x-select name="status" label="Durum"><option value="open">Acik</option><option value="in_progress">Islemde</option><option value="done">Tamam</option></x-select>
                <x-input name="title" label="Baslik" />
                <div class="md:col-span-2"><x-textarea name="notes" label="Not" /></div>
                <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white md:col-span-2">Kaydet</button>
            </div>
        </form>
        <section class="overflow-hidden rounded-md border border-slate-200 bg-white">
            <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Kayit</th><th class="p-3">Oda</th><th class="p-3">Durum</th><th class="p-3">Tarih</th></tr></thead><tbody>@foreach($logs as $log)<tr class="border-t border-slate-100"><td class="p-3"><strong>{{ $log->title }}</strong><div class="text-slate-500">{{ $log->type }}</div></td><td class="p-3">{{ $log->room->name }}<div class="text-slate-500">{{ $log->room->location->name }}</div></td><td class="p-3">{{ $log->status }}</td><td class="p-3">{{ $log->created_at->format('d.m.Y') }}</td></tr>@endforeach</tbody></table>
            <div class="p-3">{{ $logs->links() }}</div>
        </section>
    </div>
</x-layouts.app>
