<?php

namespace App\Http\Controllers;

use App\Events\PembayaranDikonfirmasi;
use App\Models\Pembayaran;
use App\Services\KonsultasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function __construct(protected KonsultasiService $service) {}

    /**
     * Konfirmasi pembayaran setelah user klik "Konfirmasi Pembayaran".
     * Di production, ini akan diintegrasikan dengan webhook payment gateway.
     */
    public function konfirmasi(Request $request): JsonResponse
    {
        $request->validate([
            'metode'       => 'required|in:qris,bca,gopay,ovo',
            'konsultan_id' => 'required|exists:konsultans,id',
            'paket'        => 'required|in:1,2,3',
        ]);

        $paketHarga = [1 => 50000, 2 => 90000, 3 => 130000];
        $harga      = $paketHarga[$request->paket];

        $pembayaran = Pembayaran::create([
            'nama_klien'        => session('onboarding.nama', 'Klien'),
            'email_klien'       => session('onboarding.email', ''),
            'hp_klien'          => session('onboarding.hp', ''),
            'bidang_hukum'      => session('onboarding.bidang', ''),
            'deskripsi_keluhan' => session('onboarding.keluhan', ''),
            'konsultan_id'      => $request->konsultan_id,
            'paket'        => $request->paket,
            'metode'       => $request->metode,
            'harga'        => $harga,
            'biaya_layanan'=> 5000,
            'total'        => $harga + 5000,
            'status'       => 'menunggu',
            'jadwal_at'    => session('onboarding.jadwal_at'),
            // Kita belum ada kolom 'expires_at' di DB secara default, jadi kita andalkan waktu created_at + 3 menit
        ]);

        return response()->json([
            'success'       => true,
            'redirect'      => route('pembayaran.invoice', $pembayaran->id),
        ]);
    }

    public function invoice($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        if ($pembayaran->status === 'lunas') {
            $konsultasi = \App\Models\Konsultasi::where('pembayaran_id', $pembayaran->id)->first();
            return redirect()->route('chat.index', $konsultasi->id);
        }

        if ($pembayaran->status === 'gagal') {
            return redirect()->route('landing')->with('error', 'Waktu pembayaran telah kadaluarsa.');
        }

        // Kalkulasi sisa waktu (3 menit dari created_at)
        $expiresAt = $pembayaran->created_at->addMinutes(3);
        $sisaDetik = $expiresAt->diffInSeconds(now(), false) * -1;
        
        if ($sisaDetik <= 0) {
            $pembayaran->update(['status' => 'gagal']);
            return redirect()->route('landing')->with('error', 'Waktu pembayaran telah kadaluarsa.');
        }

        return view('pembayaran.invoice', compact('pembayaran', 'sisaDetik', 'expiresAt'));
    }

    public function sukses($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->status === 'menunggu') {
            $pembayaran->update(['status' => 'lunas']);
            
            // Buat sesi konsultasi setelah lunas
            $konsultasi = $this->service->buatKonsultasi($pembayaran);
            
            event(new PembayaranDikonfirmasi($pembayaran));
        }

        $konsultasi = \App\Models\Konsultasi::where('pembayaran_id', $pembayaran->id)->first();
        return redirect()->route('chat.index', $konsultasi->id);
    }

    public function kadaluarsa($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->status === 'menunggu') {
            $pembayaran->update(['status' => 'gagal']);
        }

        // Return json for ajax
        return response()->json(['success' => true]);
    }

    /**
     * Webhook dari payment gateway (Midtrans, Xendit, dsb.)
     */
    public function webhook(Request $request): JsonResponse
    {
        // Verifikasi signature dari payment gateway di sini
        $pembayaran = Pembayaran::where('order_id', $request->order_id)->firstOrFail();

        if ($request->transaction_status === 'settlement' && $pembayaran->status === 'menunggu') {
            $pembayaran->update(['status' => 'lunas']);
            $this->service->buatKonsultasi($pembayaran);
            event(new PembayaranDikonfirmasi($pembayaran));
        }

        return response()->json(['status' => 'ok']);
    }
}