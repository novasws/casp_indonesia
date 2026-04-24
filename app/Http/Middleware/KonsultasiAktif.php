<?php

namespace App\Http\Middleware;

use App\Models\Konsultasi;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KonsultasiAktif
{
    /**
     * Pastikan sesi konsultasi masih aktif (tidak expired) sebelum
     * mengizinkan user mengakses halaman chat atau mengirim pesan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $konsultasiId = $request->route('konsultasiId') ?? $request->route('id');

        if (!$konsultasiId) {
            abort(404, 'Konsultasi tidak ditemukan.');
        }

        $konsultasi = Konsultasi::find($konsultasiId);

        if (!$konsultasi) {
            abort(404, 'Sesi konsultasi tidak ditemukan.');
        }

        // Cek apakah waktu konsultasi sudah habis
        $durasi   = match ((int) $konsultasi->paket) { 1 => 3600, 3 => 10800, default => 7200 };
        $mulai    = $konsultasi->mulai_at?->timestamp ?? now()->timestamp;
        $expired  = (now()->timestamp - $mulai) >= $durasi;

        if ($expired && $konsultasi->status === 'aktif') {
            $konsultasi->update(['status' => 'selesai']);
        }

        // Untuk endpoint kirim-pesan, tolak jika sudah expired
        if ($expired && $request->routeIs('chat.kirim-pesan')) {
            return response()->json(['error' => 'Waktu konsultasi sudah habis.'], 403);
        }

        // Bind model ke request agar controller bisa langsung pakai
        $request->attributes->set('konsultasi', $konsultasi);

        return $next($request);
    }
}