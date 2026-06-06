# Apart Yonetim Sistemi

Laravel tabanli kapali devre apart/oda operasyon paneli.

## Moduller

- Dashboard: dolu/bos oda, giris-cikis, gelir, gider, net kâr-zarar, yaklasan odeme ve son hareketler.
- Lokasyon, oda/daire ve musteri yonetimi.
- Tarih cakismasi kontrolu olan giris/cikis ve doluluk takvimi.
- Gelir, gider, abonelik/sayac/fatura ve temizlik/bakim kayitlari.
- Lokasyon ve oda bazli kâr-zarar raporu, CSV disa aktarim.
- Laravel auth, CSRF korumasi, Eloquent ORM ve migration tabanli veritabani yapisi.

## Yerel Calistirma

Bu makinede global Composer olmadigi icin proje kokunde yerel `composer.phar` ile kurulum yapildi.

```bash
php composer.phar install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
```

Demo girisi:

```text
admin@example.com
password
```

## Veritabani

`.env.example` production/subdomain kurulumu icin MySQL varsayilaniyla gelir. Yerel demo `.env` dosyasi SQLite kullanabilir.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apart_yonetim
DB_USERNAME=root
DB_PASSWORD=
```

## Test

```bash
php artisan test
```
