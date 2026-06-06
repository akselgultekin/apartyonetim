@props(['name', 'label', 'value' => null])
<label class="block">
    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
    <select name="{{ $name }}" {{ $attributes->merge(['class' => 'mt-1 h-10 w-full rounded-md border border-slate-300 px-3 text-sm outline-none focus:border-slate-900']) }}>
        {{ $slot }}
    </select>
</label>
