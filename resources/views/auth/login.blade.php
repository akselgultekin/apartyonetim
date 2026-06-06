<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş | Apart Yönetim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                linear-gradient(135deg, rgba(45,212,191,.16) 0 1px, transparent 1px 30px),
                linear-gradient(45deg, rgba(251,191,36,.10) 0 1px, transparent 1px 34px),
                #0f172a;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-white">
<main class="grid min-h-screen lg:grid-cols-[1.1fr_.9fr]">
    <section class="flex items-center px-6 py-12 md:px-12">
        <div class="max-w-2xl">
            <div class="mb-6 grid h-14 w-14 place-items-center rounded-md border border-white/10 bg-white/10 text-cyan-200 shadow-2xl">
                <i class="fa-solid fa-hotel text-2xl"></i>
            </div>
            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-300">Kapalı devre panel</p>
            <h1 class="text-4xl font-semibold tracking-tight md:text-6xl">Apart operasyonunu tek ekrandan yönetin.</h1>
            <p class="mt-6 max-w-xl text-base leading-7 text-slate-300">Doluluk, giriş-çıkış, kira tahsilatı, abonelik, gider ve kâr-zarar raporlarını modern bir Laravel panelinde birleştirir.</p>
            <div class="mt-8 grid gap-3 text-sm text-slate-300 sm:grid-cols-3">
                <div class="rounded-md border border-white/10 bg-white/10 p-3"><i class="fa-solid fa-bed mr-2 text-cyan-300"></i>Oda takibi</div>
                <div class="rounded-md border border-white/10 bg-white/10 p-3"><i class="fa-solid fa-receipt mr-2 text-amber-300"></i>Tahsilat</div>
                <div class="rounded-md border border-white/10 bg-white/10 p-3"><i class="fa-solid fa-chart-pie mr-2 text-emerald-300"></i>Raporlar</div>
            </div>
        </div>
    </section>
    <section class="flex items-center justify-center bg-white/95 px-6 py-12 text-slate-900">
        <form method="post" action="{{ route('login.store') }}" class="w-full max-w-md rounded-md border border-slate-200 bg-white p-6 shadow-2xl shadow-slate-950/10">
            @csrf
            <h2 class="flex items-center gap-3 text-2xl font-semibold"><i class="fa-solid fa-right-to-bracket text-teal-700"></i>Panele giriş</h2>
            <p class="mt-2 text-sm text-slate-500">Demo: admin@example.com / password</p>
            @if($errors->any())
                <div class="mt-5 rounded-md bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif
            <label class="mt-6 block text-sm font-medium">E-posta</label>
            <input name="email" type="email" value="{{ old('email', 'admin@example.com') }}" class="mt-2 h-11 w-full rounded-md border border-slate-300 px-3 outline-none focus:border-slate-900">
            <label class="mt-4 block text-sm font-medium">Şifre</label>
            <input name="password" type="password" value="password" class="mt-2 h-11 w-full rounded-md border border-slate-300 px-3 outline-none focus:border-slate-900">
            <label class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300"> Beni hatırla
            </label>
            <button class="mt-6 h-11 w-full rounded-md bg-slate-950 font-semibold text-white hover:bg-slate-800">Giriş yap</button>
        </form>
    </section>
</main>
</body>
</html>
