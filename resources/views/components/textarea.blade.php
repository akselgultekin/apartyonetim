@props(['name', 'label', 'value' => null])
<label class="block">
    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
    <textarea name="{{ $name }}" rows="3" {{ $attributes->merge(['class' => 'mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-900']) }}>{{ old($name, $value) }}</textarea>
</label>
