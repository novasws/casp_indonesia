<?php

namespace App\Services;

use App\Events\KonsultasiDimulai;
use App\Models\Konsultasi;
use App\Models\Pembayaran;

class KonsultasiService
{
    /**
     * Harga paket konsultasi (dalam rupiah).
     */
    public const PAKET_HARGA = [
        1 => 50000,
        2 => 90000,
        3 => 130000,
    ];

    public const PAKET_LABEL = [
        1 => '1 jam',
        2 => '2 jam',
        3 => '3 jam',
    ];

    public const BIAYA_LAYANAN = 5000;

    /**
     * Siapkan data ringkasan pembayaran (sebelum dibuat di DB).
     */
    public function initPembayaran(int $konsultanId, int $paket, string $metode): array
    {
        $harga = self::PAKET_HARGA[$paket] ?? 90000;

        return [
            'konsultan_id'  => $konsultanId,
            'paket'         => $paket,
            'paket_label'   => self::PAKET_LABEL[$paket],
            'metode'        => $metode,
            'harga'         => $harga,
            'biaya_layanan' => self::BIAYA_LAYANAN,
            'total'         => $harga + self::BIAYA_LAYANAN,
            'total_rupiah'  => 'Rp ' . number_format($harga + self::BIAYA_LAYANAN, 0, ',', '.'),
        ];
    }

    /**
     * Buat record Konsultasi setelah pembayaran dikonfirmasi.
     * Dispatch event KonsultasiDimulai untuk broadcast ke channel chat.
     */
    public function buatKonsultasi(Pembayaran $pembayaran): Konsultasi
    {
        $konsultan = \App\Models\Konsultan::find($pembayaran->konsultan_id);
        $isTerjadwal = ($konsultan && $konsultan->status !== 'online');
        $isSibuk = !$isTerjadwal && Konsultasi::where('konsultan_id', $pembayaran->konsultan_id)
            ->where('status', 'aktif')
            ->exists();

        $status = 'aktif';
        if ($isTerjadwal) {
            $status = 'terjadwal';
        } elseif ($isSibuk) {
            $status = 'menunggu';
        }

        $konsultasi = Konsultasi::create([
            'klien_nama'        => $pembayaran->nama_klien,
            'klien_email'       => $pembayaran->email_klien,
            'klien_hp'          => $pembayaran->hp_klien,
            'bidang_hukum'      => $pembayaran->bidang_hukum,
            'deskripsi_keluhan' => $pembayaran->deskripsi_keluhan,
            'konsultan_id'      => $pembayaran->konsultan_id,
            'paket'             => $pembayaran->paket,
            'status'            => $status,
            'mulai_at'          => ($status === 'aktif') ? now() : null,
            'jadwal_at'         => $pembayaran->jadwal_at,
            'pembayaran_id'     => $pembayaran->id,
            'token_sesi'        => 'CASP-' . strtoupper(\Illuminate\Support\Str::random(6)),
        ]);

        event(new KonsultasiDimulai($konsultasi));

        return $konsultasi;
    }

    /**
     * Format angka ke rupiah.
     */
    public static function rupiah(int $angka): string
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}