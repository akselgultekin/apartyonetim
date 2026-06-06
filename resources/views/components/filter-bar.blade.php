<form method="get" class="mb-5 grid gap-3 rounded-md border border-slate-200 bg-white p-4 md:grid-cols-5">
    {{ $slot }}
    <button class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-slate-900 px-3 text-sm font-semibold text-white">
        <i data-lucide="Search" class="h-4 w-4"></i> Filtrele
    </button>
</form>
