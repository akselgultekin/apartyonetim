<x-layouts.app heading="Hesap" subheading="Admin bilgileri ve şifre değişikliği">
    <section class="shell-card max-w-2xl rounded-md border border-slate-200 bg-white p-5">
        <form method="post" action="{{ route('account.update') }}" class="grid gap-4">
            @csrf
            <x-input name="name" label="Ad soyad" :value="auth()->user()->name" />
            <x-input name="email" label="E-posta" type="email" :value="auth()->user()->email" />
            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <h2 class="font-semibold">Şifre değiştir</h2>
                <p class="mt-1 text-sm text-slate-500">Şifre değiştirmeyeceksen bu alanları boş bırak.</p>
                <div class="mt-4 grid gap-4">
                    <x-input name="current_password" label="Mevcut şifre" type="password" />
                    <x-input name="password" label="Yeni şifre" type="password" />
                    <x-input name="password_confirmation" label="Yeni şifre tekrar" type="password" />
                </div>
            </div>
            <button class="h-10 rounded-md bg-slate-900 text-sm font-semibold text-white">Güncelle</button>
        </form>
    </section>
</x-layouts.app>
