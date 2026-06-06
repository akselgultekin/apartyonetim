@props(['label', 'value', 'hint' => null, 'tone' => 'slate', 'icon' => null])
@php
    $tones = [
        'slate' => ['border-slate-200 bg-white', 'bg-slate-100 text-slate-700'],
        'green' => ['border-emerald-200 bg-emerald-50', 'bg-emerald-600 text-white'],
        'red' => ['border-rose-200 bg-rose-50', 'bg-rose-600 text-white'],
        'blue' => ['border-cyan-200 bg-cyan-50', 'bg-cyan-600 text-white'],
        'amber' => ['border-amber-200 bg-amber-50', 'bg-amber-500 text-white'],
    ];
    [$cardTone, $iconTone] = $tones[$tone] ?? $tones['slate'];
@endphp
<div class="shell-card rounded-md border {{ $cardTone }} p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <div class="text-sm text-slate-500">{{ $label }}</div>
            <div class="mt-2 text-2xl font-semibold tracking-tight">{{ $value }}</div>
        </div>
        @if($icon)
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-md {{ $iconTone }}">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
        @endif
    </div>
    @if($hint)<div class="mt-1 text-xs text-slate-500">{{ $hint }}</div>@endif
</div>
