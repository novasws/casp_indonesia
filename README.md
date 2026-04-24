# CASP Indonesia – Platform Konsultasi Hukum Online

Platform konsultasi hukum berbasis Laravel + Tailwind CSS dengan alur onboarding multi-step, sistem pembayaran, dan sesi chat real-time.

---

## Struktur Folder

```
casp-indonesia/
├── app/
│   ├── Events/
│   │   ├── KeluhanDikirim.php          # Event saat keluhan dikirim
│   │   ├── KonsultasiDimulai.php       # Event broadcast sesi dimulai
│   │   ├── PembayaranDikonfirmasi.php  # Event setelah pembayaran lunas
│   │   └── PesanTerkirim.php           # Event broadcast pesan chat
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── LandingController.php       # Halaman utama
│   │   │   ├── KeluhanController.php       # Form keluhan
│   │   │   ├── OnboardingController.php    # Multi-step onboarding
│   │   │   ├── PembayaranController.php    # Pembayaran & webhook
│   │   │   └── ChatController.php          # Sesi chat + transkrip
│   │   ├── Middleware/
│   │   │   ├── SetLocale.php               # Set locale ke Bahasa Indonesia
│   │   │   └── KonsultasiAktif.php         # Cek sesi chat belum expired
│   │   └── Requests/
│   │       ├── KeluhanRequest.php          # Validasi form keluhan
│   │       └── OnboardingStep1Request.php  # Validasi data diri
│   ├── Models/
│   │   ├── Keluhan.php
│   │   ├── Konsultan.php
│   │   ├── Konsultasi.php
│   │   ├── Pembayaran.php
│   │   └── Pesan.php
│   └── Services/
│       └── KonsultasiService.php       # Business logic konsultasi
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_konsultans_table.php
│   │   ├── ..._create_keluhans_table.php
│   │   ├── ..._create_pembayarans_table.php
│   │   ├── ..._create_konsultasis_table.php
│   │   └── ..._create_pesans_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── KonsultanSeeder.php         # Data 6 konsultan default
├── public/
│   ├── css/app.css                     # CSS global
│   ├── js/app.js                       # JS global (CSRF, toast, helpers)
│   └── index.php                       # Entry point
├── resources/views/
│   ├── layouts/app.blade.php           # Layout utama (Tailwind + DM fonts)
│   ├── partials/
│   │   ├── navbar.blade.php
│   │   └── footer.blade.php
│   ├── landing/index.blade.php         # Halaman utama + form keluhan
│   ├── onboarding/index.blade.php      # Multi-step: data diri→agent→paket→bayar
│   └── chat/index.blade.php            # Halaman sesi chat real-time
├── routes/web.php
├── .env.example
├── composer.json
└── README.md
```

---

## Persyaratan

| Kebutuhan | Versi Minimum               |
| --------- | --------------------------- |
| PHP       | 8.2                         |
| Laravel   | 11.x                        |
| MySQL     | 8.0 / MariaDB 10.5          |
| Composer  | 2.x                         |
| Node.js   | 18.x (opsional, untuk Vite) |

---

## Instalasi

### 1. Clone & Install Dependensi

```bash
git clone https://github.com/your-org/casp-indonesia.git
cd casp-indonesia
composer install
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuaikan:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=casp_indonesia
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Database & Seeder

```bash
# Buat database terlebih dahulu di MySQL:
# CREATE DATABASE casp_indonesia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

php artisan migrate
php artisan db:seed
```

Seeder akan membuat 6 konsultan default:

- Dr. Agus Santoso S.H. – Hukum Perdata
- Siti Rahayu S.H., M.Kn – Hukum Keluarga
- Budi Prakoso S.H. – Hukum Bisnis
- Rina Wulandari S.H. – Hukum Properti
- Hendra Adi S.H., M.H. – Hukum Ketenagakerjaan
- Lisa Maharani S.H. – Hukum Pidana

### 4. Jalankan Server

```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## Alur Pengguna

```
Halaman Utama (Landing)
    ├── [Mulai Konsultasi] ──→ Onboarding Step 1: Data Diri
    │                               ↓
    │                          Step 2: Pilih Konsultan
    │                               ↓
    │                          Step 3: Pilih Paket (1/2/3 jam)
    │                               ↓
    │                          Step 4: Pembayaran (QRIS/BCA/GoPay/OVO)
    │                               ↓
    │                          Halaman Chat (sesi real-time + timer)
    │                               ↓
    │                          Unduh Transkrip (.txt)
    │
    └── [Ajukan Keluhan Dulu] ──→ Form Keluhan (AJAX submit)
```

---

## Routes

| Method | URI                           | Nama                         | Deskripsi                |
| ------ | ----------------------------- | ---------------------------- | ------------------------ |
| GET    | `/`                           | `landing`                    | Halaman utama            |
| POST   | `/keluhan`                    | `keluhan.store`              | Simpan keluhan           |
| GET    | `/konsultasi`                 | `onboarding.index`           | Halaman onboarding       |
| POST   | `/konsultasi/step1`           | `onboarding.step1`           | Validasi data diri       |
| POST   | `/konsultasi/step2`           | `onboarding.step2`           | Simpan pilihan konsultan |
| POST   | `/konsultasi/step3`           | `onboarding.step3`           | Simpan pilihan paket     |
| POST   | `/konsultasi/pembayaran/init` | `onboarding.pembayaran.init` | Init pembayaran          |
| POST   | `/pembayaran/konfirmasi`      | `pembayaran.konfirmasi`      | Konfirmasi & buat sesi   |
| POST   | `/pembayaran/webhook`         | `pembayaran.webhook`         | Webhook payment gateway  |
| GET    | `/chat/{id}`                  | `chat.index`                 | Halaman chat             |
| POST   | `/chat/{id}/pesan`            | `chat.kirim-pesan`           | Kirim pesan (AJAX)       |
| GET    | `/chat/{id}/transkrip`        | `chat.transkrip`             | Download transkrip .txt  |

---

## Fitur

- ✅ Landing page lengkap (hero, layanan, cara kerja, form keluhan)
- ✅ Form keluhan dengan validasi server-side (AJAX)
- ✅ Onboarding multi-step (5 langkah)
- ✅ Pilih konsultan dari database
- ✅ 3 paket durasi konsultasi
- ✅ 4 metode pembayaran (QRIS, BCA, GoPay, OVO)
- ✅ Countdown timer pembayaran (15 menit)
- ✅ Sesi chat dengan timer countdown sesuai paket
- ✅ Progress bar sisa waktu
- ✅ Download transkrip percakapan (.txt)
- ✅ Events & Listeners untuk notifikasi
- ✅ Middleware cek sesi aktif
- ✅ Seeder data konsultan

---

## Pengembangan Lanjutan (TODO)

- [ ] Integrasi Midtrans / Xendit untuk pembayaran nyata
- [ ] Laravel Echo + Pusher untuk chat real-time (WebSocket)
- [ ] Notifikasi email via Mailable (keluhan, konfirmasi bayar)
- [ ] Panel admin untuk kelola konsultan & keluhan
- [ ] Rating & ulasan setelah sesi selesai
- [ ] Autentikasi pengguna (opsional)

---

## Lisensi

Proprietary – © {{ date('Y') }} CASP Indonesia. Hak cipta dilindungi.
