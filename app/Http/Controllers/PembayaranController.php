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
        $total      = $harga + 5000;
        $order_id   = 'CASP-' . uniqid();

        $pembayaran = Pembayaran::create([
            'order_id'          => $order_id,
            'nama_klien'        => session('onboarding.nama', 'Klien'),
            'email_klien'       => session('onboarding.email', 'klien@example.com'),
            'hp_klien'          => session('onboarding.hp', '08123456789'),
            'bidang_hukum'      => session('onboarding.bidang', '-'),
            'deskripsi_keluhan' => session('onboarding.keluhan', '-'),
            'konsultan_id'      => $request->konsultan_id,
            'paket'             => $request->paket,
            'metode'            => $request->metode,
            'harga'             => $harga,
            'biaya_layanan'     => 5000,
            'total'             => $total,
            'status'            => 'menunggu',
            'jadwal_at'         => session('onboarding.jadwal_at'),
        ]);

        // --- INTEGRASI MIDTRANS ---
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $pembayaran->nama_klien,
                'email' => $pembayaran->email_klien,
                'phone' => $pembayaran->hp_klien,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $pembayaran->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage(), [
                'order_id' => $order_id,
                'params' => $params
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi penyedia pembayaran: ' . $e->getMessage()
            ], 500);
        }
        // --- END INTEGRASI MIDTRANS ---

        return response()->json([
            'success'       => true,
            'redirect'      => route('pembayaran.invoice', $pembayaran->id),
        ]);
    }

    public function invoice($id)
    {
        $pembayaran = Pembayaran::with('konsultan')->findOrFail($id);
        
        if ($pembayaran->status === 'lunas') {
            $konsultasi = \App\Models\Konsultasi::where('pembayaran_id', $pembayaran->id)->first();
            return redirect()->route('chat.index', $konsultasi->id ?? 1);
        }

        if ($pembayaran->status === 'gagal') {
            return redirect()->route('landing')->with('error', 'Waktu pembayaran telah kadaluarsa.');
        }

        // Kalkulasi sisa waktu (15 menit untuk Midtrans biasanya lebih aman)
        $expiresAt = $pembayaran->created_at->addMinutes(15);
        $sisaDetik = (int) ($expiresAt->diffInSeconds(now(), false) * -1);
        
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
        return redirect()->route('chat.index', $konsultasi->id ?? 1);
    }

    public function kadaluarsa($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->status === 'menunggu') {
            $pembayaran->update(['status' => 'gagal']);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Webhook dari Midtrans
     */
    public function webhook(Request $request): JsonResponse
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $pembayaran = Pembayaran::where('order_id', $order_id)->firstOrFail();

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $pembayaran->update(['status' => 'menunggu']);
                } else {
                    $pembayaran->update(['status' => 'lunas']);
                }
            }
        } elseif ($transaction == 'settlement') {
            $pembayaran->update(['status' => 'lunas']);
        } elseif ($transaction == 'pending') {
            $pembayaran->update(['status' => 'menunggu']);
        } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $pembayaran->update(['status' => 'gagal']);
        }

        if ($pembayaran->status === 'lunas') {
            $this->service->buatKonsultasi($pembayaran);
            event(new PembayaranDikonfirmasi($pembayaran));
        }

        return response()->json(['status' => 'ok']);
    }
}