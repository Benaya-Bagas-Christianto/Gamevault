<div align="center">
<img src="https://raw.githubusercontent.com/Benaya-Bagas-Christianto/Gamevault/main/public/images/logo-gamevault.png" alt="GameVault Logo" width="160" style="background:#0d0d0d; border-radius:16px; padding:20px;" />

# GameVault

### Platform Jual Beli Game Digital PC — Lisensi Resmi, Pengiriman Instan

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 📖 Tentang GameVault

**GameVault** adalah platform e-commerce khusus game digital PC yang dibangun menggunakan **Laravel 13** dengan desain antarmuka modern bergaya gelap (*dark mode*). Platform ini memungkinkan pengguna untuk membeli game digital dengan lisensi resmi, menerima *license key* secara instan melalui email, serta menikmati berbagai fitur eksklusif seperti sistem poin reward dan AI assistant berbasis Google Gemini.

### ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🛒 **Toko Game Digital** | Katalog ratusan game PC dengan filter genre, harga, dan publisher |
| 🔑 **Instant Key Delivery** | License key dikirim otomatis ke email setelah pembayaran berhasil |
| 💳 **Payment Gateway** | Mendukung QRIS, Transfer Bank, GoPay, OVO, DANA, dan Alfamart via Midtrans |
| 🤖 **VaultBot AI** | Asisten chat berbasis Google Gemini untuk membantu pengguna |
| 🎁 **Sistem Poin** | Dapatkan poin dari setiap pembelian dan tukarkan dengan hadiah |
| 📚 **Library** | Kelola seluruh game yang sudah dibeli dalam satu halaman |
| ❤️ **Wishlist** | Simpan game favorit untuk dibeli nanti |
| 🔄 **Sistem Refund** | Ajukan refund dalam 14 hari dengan alasan yang valid |
| 🔐 **Keamanan Berlapis** | Autentikasi PIN, OTP via email, dan notifikasi keamanan |
| 👨‍💼 **Admin Panel** | Dashboard admin lengkap untuk kelola game, transaksi, user, dan refund |

---

## 🔌 API yang Digunakan

### 1. 🎮 Steam Store API
> **Sumber data game otomatis langsung dari Valve**

Digunakan untuk mengambil data game secara otomatis dari database Steam, termasuk:
- 📝 Deskripsi dan detail game
- 🖼️ Header image, screenshot, dan trailer video
- 🏷️ Genre, kategori, dan developer/publisher
- 💻 Spesifikasi minimum & rekomendasi PC
- 💰 Informasi harga referensi

**Endpoint:**
```
GET https://store.steampowered.com/api/appdetails?appids={APP_ID}&cc=id&l=indonesian
GET https://store.steampowered.com/api/storesearch/?term={QUERY}&l=indonesian
```
> ✅ **Gratis** — Tidak memerlukan API Key

---

### 2. 💳 Midtrans Payment Gateway
> **Pemrosesan pembayaran yang aman dan terpercaya**

Digunakan untuk memproses semua transaksi pembelian di GameVault. Mendukung berbagai metode pembayaran populer di Indonesia.

**Metode Pembayaran yang Didukung:**
- 📱 QRIS (semua e-wallet)
- 🏦 Transfer Bank Virtual Account (BCA, BNI, BRI, Mandiri, Permata)
- 💚 GoPay & OVO
- 🏪 Alfamart & Indomaret
- 💳 Kartu Kredit / Debit (Visa & Mastercard)

**Cara Mendapatkan API Key:**
1. Daftar di [https://dashboard.midtrans.com](https://dashboard.midtrans.com)
2. Masuk ke **Settings → Access Keys**
3. Salin **Server Key** dan **Client Key** (Gunakan *Sandbox* untuk testing)

**Konfigurasi di `.env`:**
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

---

### 3. 🤖 Google Gemini AI API
> **Otak di balik VaultBot — Asisten AI GameVault**

Digunakan untuk fitur **VaultBot**, asisten chat yang membantu pengguna menjawab pertanyaan seputar game, merekomendasikan game, dan menjelaskan fitur-fitur GameVault.

**Model yang digunakan:** `gemini-2.0-flash`

**Cara Mendapatkan API Key:**
1. Kunjungi [https://aistudio.google.com/app/apikey](https://aistudio.google.com/app/apikey)
2. Klik **Create API Key**
3. Salin API Key yang dihasilkan

**Konfigurasi di `.env`:**
```env
GEMINI_API_KEY=AIzaSy_xxxxxxxxxxxxxxxxxxxxxx
```

---

### 4. 📧 SMTP Email (Mail Driver)
> **Notifikasi email untuk semua aktivitas penting**

Digunakan untuk mengirim:
- 🔑 License key setelah pembelian berhasil
- 🔢 Kode OTP untuk ganti email & reset PIN
- ✅ Konfirmasi refund
- 🔔 Notifikasi keamanan (ganti password/PIN)

**Konfigurasi di `.env` (contoh menggunakan Gmail):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD=xxxx_xxxx_xxxx_xxxx  # App Password Gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=emailkamu@gmail.com
MAIL_FROM_NAME="GameVault"
```
> 💡 Untuk Gmail, aktifkan **2-Factor Authentication** lalu buat **App Password** di pengaturan akun Google.

---

## 🚀 Cara Instalasi & Penggunaan

### Prasyarat
- **PHP** >= 8.2 dengan ekstensi: `mbstring`, `openssl`, `pdo`, `fileinfo`
- **Composer** >= 2.x
- **MySQL** >= 8.0
- **Node.js** >= 18.x & **npm**

### Langkah-langkah

**1. Clone repositori**
```bash
git clone https://github.com/Benaya-Bagas-Christianto/Gamevault.git
cd Gamevault
```

**2. Install dependensi PHP**
```bash
composer install
```

**3. Install dependensi JavaScript**
```bash
npm install
```

**4. Konfigurasi environment**
```bash
# Salin file .env contoh
cp .env.example .env

# Generate application key
php artisan key:generate
```

**5. Edit file `.env`** — Sesuaikan konfigurasi berikut:
```env
APP_NAME="GameVault"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gamevault
DB_USERNAME=root
DB_PASSWORD=

# Isi API key sesuai panduan di atas
MIDTRANS_SERVER_KEY=...
MIDTRANS_CLIENT_KEY=...
GEMINI_API_KEY=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

**6. Buat database & jalankan migrasi**
```bash
# Buat database bernama 'gamevault' di MySQL terlebih dahulu, lalu:
php artisan migrate
```

**7. Buat symlink untuk penyimpanan file (gambar/avatar)**
```bash
php artisan storage:link
```

**8. (Opsional) Jalankan seeder untuk data awal**
```bash
php artisan db:seed
```

**9. Build aset frontend**
```bash
npm run build
# Atau untuk mode development dengan hot-reload:
npm run dev
```

**10. Jalankan server**
```bash
php artisan serve
```

Buka browser dan akses: **[http://localhost:8000](http://localhost:8000)** 🎉

---

## 📂 Struktur Proyek Penting

```
GameVault/
├── app/
│   ├── Http/Controllers/       # Logika utama aplikasi
│   │   ├── Admin/              # Controller untuk Admin Panel
│   │   ├── Auth/               # Autentikasi (login, register, reset)
│   │   ├── AiChatController.php    # VaultBot (Gemini AI)
│   │   ├── CheckoutController.php  # Proses checkout & Midtrans
│   │   └── ...
│   ├── Models/                 # Model database (Eloquent ORM)
│   └── Mail/                   # Template email (OTP, License Key, dll)
├── resources/views/
│   ├── home/                   # Halaman utama & toko
│   ├── game/                   # Halaman detail game
│   ├── admin/                  # Admin Panel
│   ├── profile/                # Halaman profil pengguna
│   ├── checkout/               # Alur checkout & pembayaran
│   └── layouts/                # Layout utama (app.blade.php, admin.blade.php)
├── public/
│   ├── css/app.css             # CSS utama (custom design system)
│   └── images/                 # Aset gambar (logo, payment icons)
├── database/migrations/        # Skema database
└── routes/web.php              # Definisi semua route
```

---

## 🛡️ Keamanan

- File `.env` (berisi API Key & password) sudah dikecualikan dari repositori ini via `.gitignore`
- Semua password di-*hash* menggunakan `bcrypt`
- PIN dikunci dengan validasi kekuatan (*strong PIN rule*)
- Setiap ganti password/PIN/email akan mengirimkan notifikasi keamanan ke email pengguna

---

## 👨‍💻 Developer

**Benaya Bagas Christianto**

[![GitHub](https://img.shields.io/badge/GitHub-Benaya--Bagas--Christianto-181717?style=flat-square&logo=github)](https://github.com/Benaya-Bagas-Christianto)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<div align="center">

Dibuat dan dirancang oleh **[Benaya Bagas Christianto](https://github.com/Benaya-Bagas-Christianto)**

*Berdedikasi untuk mengembangkan platform game digital yang modern, aman, dan menyenangkan bagi para gamer Indonesia.*

</div>
