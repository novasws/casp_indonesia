<?php

namespace App\Http\Controllers;

use App\Events\PesanTerkirim;
use App\Models\Konsultasi;
use App\Models\Pesan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Tampilkan halaman chat konsultasi.
     */
    public function index(int $konsultasiId): View
    {
        $konsultasi = Konsultasi::with('konsultan')
            ->where('id', $konsultasiId)
            ->firstOrFail();

        $pesan = $konsultasi->pesans()
            ->orderBy('created_at')
            ->get();

        $durasi = match ((int) $konsultasi->paket) {
            1 => 3600,
            3 => 10800,
            default => 7200,
        };

        // Sisa waktu dalam detik
        $mulai      = $konsultasi->mulai_at?->timestamp ?? now()->timestamp;
        $sisaDetik  = max(0, $durasi - (now()->timestamp - $mulai));

        $antreanKe = 0;
        $estimasiTungguDetik = 0;

        if (in_array($konsultasi->status, ['menunggu', 'terjadwal'])) {
            $antreanKe = Konsultasi::where('konsultan_id', $konsultasi->konsultan_id)
                ->whereIn('status', ['menunggu', 'terjadwal'])
                ->where('created_at', '<=', $konsultasi->created_at)
                ->count();
                
            // Sisa waktu sesi yang sedang aktif
            $aktifSesi = Konsultasi::where('konsultan_id', $konsultasi->konsultan_id)
                ->where('status', 'aktif')
                ->first();
                
            if ($aktifSesi) {
                $durasiAktif = match ((int) $aktifSesi->paket) {
                    1 => 3600,
                    3 => 10800,
                    default => 7200,
                };
                $mulaiAktif = $aktifSesi->mulai_at?->timestamp ?? now()->timestamp;
                $estimasiTungguDetik += max(0, $durasiAktif - (now()->timestamp - $mulaiAktif));
            }

            // Waktu dari antrean di depan (jika ada)
            $antreanDiDepan = Konsultasi::where('konsultan_id', $konsultasi->konsultan_id)
                ->whereIn('status', ['menunggu', 'terjadwal'])
                ->where('created_at', '<', $konsultasi->created_at)
                ->get();
                
            foreach ($antreanDiDepan as $ad) {
                $estimasiTungguDetik += match ((int) $ad->paket) {
                    1 => 3600,
                    3 => 10800,
                    default => 7200,
                };
            }
        }
        $jadwalDetik = 0;
        if ($konsultasi->status === 'terjadwal' && $konsultasi->jadwal_at) {
            $jadwalDetik = max(0, $konsultasi->jadwal_at->timestamp - now()->timestamp);
        }

        $estimasiJamMulai = 'Menunggu';
        if ($konsultasi->konsultan->status === 'offline') {
            if ($konsultasi->konsultan->jadwal_shift) {
                $parts = explode('-', $konsultasi->konsultan->jadwal_shift);
                $startShift = trim($parts[0] ?? '');
                if (preg_match('/^(\d{1,2}):(\d{2})$/', $startShift, $m)) {
                    $baseTimeSec = ($m[1] * 3600) + ($m[2] * 60);
                    $finalTimeSec = $baseTimeSec + $estimasiTungguDetik;
                    
                    $finalH = floor($finalTimeSec / 3600) % 24;
                    $finalM = floor(($finalTimeSec % 3600) / 60);
                    $estimasiJamMulai = sprintf('%02d:%02d', $finalH, $finalM) . ' WIB';
                }
            } else {
                $estimasiJamMulai = 'Menunggu Shift';
            }
        } else {
            // Konsultan Online: waktu sekarang + estimasi tunggu
            $estimasiJamMulai = now()->setTimezone('Asia/Jakarta')
                ->addSeconds($estimasiTungguDetik)
                ->format('H:i') . ' WIB';
        }

        return view('chat.index', compact('konsultasi', 'pesan', 'sisaDetik', 'durasi', 'antreanKe', 'estimasiTungguDetik', 'jadwalDetik', 'estimasiJamMulai'));
    }

    /**
     * Kirim pesan baru (AJAX).
     */
    public function kirimPesan(Request $request, int $konsultasiId): JsonResponse
    {
        $request->validate(['isi' => 'required|string|max:5000']);

        $konsultasi = Konsultasi::findOrFail($konsultasiId);

        // Pastikan sesi masih aktif
        if ($konsultasi->status !== 'aktif') {
            return response()->json(['error' => 'Sesi konsultasi sudah berakhir.'], 422);
        }

        $pesan = Pesan::create([
            'konsultasi_id' => $konsultasiId,
            'pengirim'      => 'klien',
            'isi'           => $request->isi,
        ]);

        event(new PesanTerkirim($pesan));

        return response()->json([
            'success'    => true,
            'pesan'      => [
                'id'         => $pesan->id,
                'pengirim'   => $pesan->pengirim,
                'isi'        => $pesan->isi,
                'dikirim_at' => $pesan->created_at->format('H.i'),
            ],
        ]);
    }

    /**
     * Ambil pesan terbaru (Polling AJAX).
     */
    public function fetchPesan(int $konsultasiId): JsonResponse
    {
        $konsultasi = Konsultasi::findOrFail($konsultasiId);
        $pesans = $konsultasi->pesans()->orderBy('created_at')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'pengirim' => $p->pengirim,
                'isi' => $p->isi,
                'waktu' => $p->waktu,
            ];
        });

        return response()->json([
            'success' => true,
            'status'  => $konsultasi->status,
            'pesans'  => $pesans
        ]);
    }

    /**
     * Cek status sesi (Polling AJAX) untuk UI menunggu/terjadwal.
     */
    public function status(int $konsultasiId): JsonResponse
    {
        $konsultasi = Konsultasi::findOrFail($konsultasiId);
        return response()->json([
            'success' => true,
            'status'  => $konsultasi->status
        ]);
    }

    /**
     * Unduh transkrip percakapan sebagai file .txt.
     */
    public function transkrip(int $konsultasiId)
    {
        $konsultasi = Konsultasi::with(['konsultan', 'pesans'])->findOrFail($konsultasiId);
        
        return view('chat.transkrip-pdf', compact('konsultasi'));
    }
}