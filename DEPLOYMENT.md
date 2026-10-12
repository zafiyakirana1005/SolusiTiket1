# Panduan Deploy

## Persyaratan Hosting

- PHP 8.3 atau lebih baru dengan ekstensi yang dilaporkan oleh `composer check-platform-reqs`.
- Composer 2, MySQL/MariaDB, dan document root yang diarahkan ke folder `public`.
- Direktori `storage` dan `bootstrap/cache` dapat ditulis oleh proses PHP.
- HTTPS aktif untuk domain toko.

## Environment Produksi

Atur `.env` di server, jangan unggah `.env` lokal:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-toko-anda
APP_KEY=base64:KEY_UNIK_YANG_DISIMPAN_PERMANEN
DB_CONNECTION=mysql
SESSION_SECURE_COOKIE=true
```

Isi kredensial database dan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, serta `DB_PASSWORD` dari panel hosting. Buat `APP_KEY` satu kali pada instalasi pertama dan pertahankan nilainya untuk deploy berikutnya.

## Langkah Deploy

Backup database sebelum migrasi, lalu dari root proyek jalankan:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan app:create-admin
php artisan config:cache
php artisan view:cache
```

Migrasi role mengubah default role menjadi `staff` dan menurunkan akun demo `test@example.com`; akun lain dipertahankan. Command `app:create-admin` membuat atau mempromosikan akun admin secara interaktif. Jangan jalankan seeder demo di database produksi. Jangan gunakan `composer setup` untuk deploy karena script tersebut membuat ulang app key dan menjalankan langkah setup lokal.

Pastikan folder `public/assets` ikut diunggah. Blade storefront saat ini memuat aset statis tersebut langsung, jadi Node/Vite tidak diperlukan untuk halaman yang aktif. Hindari `route:cache` sampai route closure di `routes/web.php` dipindahkan ke controller.

## Sebelum Menerima Pesanan

Checkout saat ini belum terhubung ke payment gateway, belum menampilkan instruksi rekening pada halaman sukses, dan memakai ongkir tetap Rp15.000. Pembayaran perlu diverifikasi manual melalui admin. Konfigurasikan provider pembayaran atau siapkan instruksi pembayaran dan proses operasional yang benar sebelum menerima transaksi publik.