<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giris | Apart Yonetim</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white">
<main class="grid min-h-screen lg:grid-cols-[1.1fr_.9fr]">
    <section class="flex items-center px-6 py-12 md:px-12">
        <div class="max-w-2xl">
            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-300">Kapali devre panel</p>
            <h1 class="text-4xl font-semibold tracking-tight md:text-6xl">Apart operasyonunu tek ekrandan yonetin.</h1>
            <p class="mt-6 max-w-xl text-base leading-7 text-slate-300">Doluluk, giris-cikis, kira tahsilati, abonelik, gider ve kâr-zarar raporlarini modern bir Laravel panelinde birlestirir.</p>
        </div>
    </section>
    <section class="flex items-center justify-center bg-white px-6 py-12 text-slate-900">
        <form method="post" action="{{ route('login.store') }}" class="w-full max-w-md">
            @csrf
            <h2 class="text-2xl font-semibold">Panele giris</h2>
            <p class="mt-2 text-sm text-slate-500">Demo: admin@example.com / password</p>
            @if($errors->any())
                <div class="mt-5 rounded-md bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif
            <label class="mt-6 block text-sm font-medium">E-posta</label>
            <input name="email" type="email" value="{{ old('email', 'admin@example.com') }}" class="mt-2 h-11 w-full rounded-md border border-slate-300 px-3 outline-none focus:border-slate-900">
            <label class="mt-4 block text-sm font-medium">Sifre</label>
            <input name="password" type="password" value="password" class="mt-2 h-11 w-full rounded-md border border-slate-300 px-3 outline-none focus:border-slate-900">
            <label class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300"> Beni hatirla
            </label>
            <button class="mt-6 h-11 w-full rounded-md bg-slate-950 font-semibold text-white hover:bg-slate-800">Giris yap</button>
        </form>
    </section>
</main>
</body>
</html>
