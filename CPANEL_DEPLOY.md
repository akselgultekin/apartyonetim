# cPanel Subdomain Kurulum Notlari

Bu proje Laravel uygulamasidir. Canli kurulumda web root mutlaka projenin `public` klasoru olmalidir.

## 1. Subdomain

cPanel > Domains/Subdomains uzerinden subdomain olusturun.

Ornek:

```text
panel.domain.com
```

Document root su sekilde olmalidir:

```text
/home/CPANEL_KULLANICI/apart-yonetim/public
```

Eger cPanel document root olarak sadece `public_html/...` kabul ediyorsa, repo klasorunu buna gore konumlandirin ama domain root yine `public` klasorunu gostersin.

## 2. Git ile kodu cekme

cPanel > Git Version Control ile GitHub repo URL'sini ekleyin veya SSH terminalden:

```bash
git clone GITHUB_REPO_URL apart-yonetim
cd apart-yonetim
```

## 3. Composer

Sunucuda Composer varsa:

```bash
composer install --no-dev --optimize-autoloader
```

Composer yoksa lokal makinede vendor klasoru uretilip hosting'e yuklenebilir; ancak en sagliklisi hostingde Composer kullanmaktir.

## 4. .env

Sunucuda `.env.example` dosyasini `.env` olarak kopyalayin ve MySQL bilgilerini girin:

```env
APP_NAME="Apart Yonetim"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://panel.domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=CPANEL_DB_ADI
DB_USERNAME=CPANEL_DB_KULLANICI
DB_PASSWORD=CPANEL_DB_SIFRE
```

Ardindan:

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Demo veri gerekiyorsa sadece ilk kurulumda:

```bash
php artisan db:seed --force
```

## 5. Dizin izinleri

`storage` ve `bootstrap/cache` yazilabilir olmalidir.

```bash
chmod -R 775 storage bootstrap/cache
```

## 6. Guncelleme

Yeni push sonrasi:

```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
