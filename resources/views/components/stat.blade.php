@props(['label', 'value', 'hint' => null, 'tone' => 'slate'])
@php
    $tones = [
        'slate' => 'border-slate-200 bg-white',
        'green' => 'border-emerald-200 bg-emerald-50',
        'red' => 'border-rose-200 bg-rose-50',
        'blue' => 'border-cyan-200 bg-cyan-50',
        'amber' => 'border-amber-200 bg-amber-50',
    ];
@endphp
<div class="rounded-md border {{ $tones[$tone] ?? $tones['slate'] }} p-4">
    <div class="text-sm text-slate-500">{{ $label }}</div>
    <div class="mt-2 text-2xl font-semibold">{{ $value }}</div>
    @if($hint)<div class="mt-1 text-xs text-slate-500">{{ $hint }}</div>@endif
</div>
