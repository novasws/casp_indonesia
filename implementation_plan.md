# Konversi Form Keluhan Menjadi Live Chat Customer Service Profesional

Saat ini, modul Customer Service hanya berupa form satu arah (tiket keluhan) di mana pengguna mengisi keluhan dan Admin membacanya dari dashboard untuk ditindaklanjuti secara offline (via WhatsApp/Email). 

Sesuai dengan permintaan Anda untuk mengimplementasikan di dunia nyata, form statis tersebut akan dirombak menjadi **Sistem Live Chat Customer Service yang interaktif dan profesional** di mana Klien dan Superadmin dapat mengobrol secara real-time.

## User Review Required

> [!IMPORTANT]
> Mohon baca rancangan sistem di bawah ini. Fitur ini akan merombak sedikit cara kerja dashboard Keluhan dan popup CS di sisi klien. Jika Anda setuju dengan skema desain database dan UI, silakan setujui rencana ini agar saya dapat memulai pengerjaannya.

## Proposed Changes

---

### 1. Database & Models (Penyimpanan Chat CS)
Sistem butuh menyimpan riwayat chat bolak-balik antara Klien dan Superadmin.
- Menambahkan **migration baru** untuk tabel `keluhan_pesans`.
- Menambahkan struktur tabel `keluhan_pesans`: `id`, `keluhan_id`, `pengirim` (klien/admin), `isi`, dan `timestamps`.
- Memodifikasi/Membuat Model **`KeluhanPesan`** untuk menyimpan isi chat CS.
- Mengubah skema `keluhans` (opsional): Menambahkan kolom `token_sesi` agar sesi chat dapat dilanjutkan bila Klien me-refresh browser (data sesi akan disimpan di `localStorage` peramban klien).

---

### 2. Controller & Routing (Mesin Penggerak Chat)
Menambahkan dan menyesuaikan rute/logika utama:
- [NEW] **Endpoint Client-side**: 
    - `POST /keluhan/start` (Saat klien mengirim kendala pertama, ini akan membuka *room*).
    - `GET /keluhan/{token}/fetch` (AJAX untuk memuat pesan baru dari Admin).
    - `POST /keluhan/{token}/send` (Menerima ketikan balasan dari Klien).
- [NEW] **Endpoint Admin-side** (`AdminController`):
    - `GET /admin/keluhan/{id}/chat` (Tampilan antarmuka Dashboard Admin untuk membalas).
    - `POST /admin/keluhan/{id}/reply` (AJAX untuk mengirim pesan Admin ke Klien).
    - `GET /admin/keluhan/{id}/fetch` (AJAX untuk sinkronisasi pesan).
    - `POST /admin/keluhan/{id}/selesaikan` (Menutup sesi CS secara permanen).

---

### 3. Tampilan Interface (Frontend Klien)
Mendesain ulang *Floating CS Modal* yang ada pada `resources/views/layouts/app.blade.php`:
- Mengubah cara kerja interaksi: Begitu Klien menekan tombol `Kirim Laporan`, formulir tidak akan hilang menampilkan "Laporan Diterima", melainkan langsung bertransisi (animasi) menjadi layar obrolan (*Chat UI*) yang bersih dan modern seperti Tawk.to atau WhatsApp Web.
- Jika Klien kembali berinteraksi esok harinya dan sesinya masih aktif, maka ia langsung melihat layar riwayat chat tanpa menginput formulir lagi.
- Menambahkan desain gelembung (*bubble chat*) untuk Klien dan Admin lengkap dengan indikator waktu.

---

### 4. Tampilan Interface (Dashboard Superadmin)
Mendesain ulang fitur Manajemen Keluhan:
- Memodifikasi tampilan tabel **Keluhan Masuk** pada `resources/views/admin/keluhan/index.blade.php` agar tombol `Tinjau` berfungsi membuka *room chat*.
- Membuat halaman baru `resources/views/admin/keluhan/chat.blade.php`, mengadopsi estetika antarmuka UI desain Konsultasi yang sudah keren, namun dikhususkan untuk Superadmin yang bertugas sebagai *Customer Support*.

## Open Questions

> [!WARNING]
> Setelah sesi chat Customer Service selesai atau ditutup oleh Superadmin, apakah Anda ingin Klien yang bersangkutan kembali melihat "Form Input Awal" jika menekan tombol CS, atau mereka tetap bisa melihat rekam jejak obrolan mereka sebelumnya meskipun tombol ketik sudah dinonaktifkan? 

## Verification Plan

### Manual Verification
1. Mensimulasikan klien mengisi form CS di Landing Page, lalu muncul layar Live Chat. Klien mengetik sebuah pertanyaan tambahan.
2. Membuka Dashboard Superadmin, mengeklik tombol "Tinjau" pada keluhan terbaru, dan melihat ruang interaksi obrolan terbuka.
3. Superadmin mengirimkan jawaban balasan, dan membuktikan balasan tersebut langsung muncul secara otomatis (real-time) di layar Landing Page Klien. 
4. Menutup atau menyelesaikan kasus CS, memastikan status di Dasbor berubah dari 'menunggu' menjadi 'selesai'.
