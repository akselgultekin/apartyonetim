# Terminal Olmadan cPanel Kurulum

Terminal yoksa Composer ve Artisan komutlari sunucuda calistirilamaz. Bu durumda kurulum File Manager, phpMyAdmin ve hazir zip paketiyle yapilir.

## 1. Dosyalar

Hazirlanan zip paketini `/home/boluroyalkonakla/apartyonetim` klasorune yukleyip orada extract edin.

Subdomain document root:

```text
/home/boluroyalkonakla/apartyonetim/public
```

## 2. .env

`/home/boluroyalkonakla/apartyonetim/.env.example` dosyasini `.env` olarak kopyalayin.

En az su alanlari duzenleyin:

```env
APP_NAME="Apart Yonetim"
APP_ENV=production
APP_KEY=base64:VTmy7DXms1X45I9OUfcOEZe4pQk9jA6lH2YZZ6eV6a4=
APP_DEBUG=false
APP_URL=https://yonetim.boluroyalkonaklama.com
APP_LOCALE=tr
APP_FALLBACK_LOCALE=tr

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=CPANEL_DB_ADI
DB_USERNAME=CPANEL_DB_KULLANICI
DB_PASSWORD=CPANEL_DB_SIFRE
```

## 3. Veritabani

cPanel > MySQL Databases:

- Database olusturun.
- User olusturun.
- User'i database'e ekleyip `ALL PRIVILEGES` verin.

Sonra phpMyAdmin'e girin, veritabanini secin ve su dosyayi import edin:

```text
database/install_mysql.sql
```

Import sonrasi demo giris:

```text
admin@example.com
password
```

## 4. Izinler

File Manager ile su klasorlerin yazilabilir oldugundan emin olun:

```text
storage
bootstrap/cache
```

Gerekirse izinleri `775` yapin.

## 5. Hata Alirsaniz

`500` hatasi gelirse once `APP_DEBUG=true` yapip sayfayi yenileyin. Hatayi gordukten sonra tekrar `APP_DEBUG=false` yapin.
