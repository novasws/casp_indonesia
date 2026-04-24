<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardingStep1Request;
use App\Models\Konsultan;
use App\Services\KonsultasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function __construct(protected KonsultasiService $service) {}

    /**
     * Tampilkan halaman onboarding multi-step.
     */
    public function index(): \Illuminate\View\View
    {
        $konsultan = Konsultan::where('is_superadmin', false)->get();

        $paket = [
            1 => ['label' => '1 jam',  'harga' => 50000,  'fitur' => ['Chat 60 menit', '1 topik', 'Transkrip'],                   'fitur_off' => ['Review dokumen']],
            2 => ['label' => '2 jam',  'harga' => 90000,  'fitur' => ['Chat 120 menit', '2 topik', 'Transkrip', 'Review 1 dokumen'], 'fitur_off' => [], 'populer' => true],
            3 => ['label' => '3 jam',  'harga' => 130000, 'fitur' => ['Chat 180 menit', 'Unlimited topik', 'Transkrip', 'Review 3 dokumen'], 'fitur_off' => []],
        ];

        $bidang_hukum = [
            'Hukum Perdata',
            'Hukum Keluarga',
            'Hukum Bisnis',
            'Hukum Properti',
            'Hukum Ketenagakerjaan',
            'Hukum Pidana',
            'Lainnya',
        ];

        return view('onboarding.index', compact('konsultan', 'paket', 'bidang_hukum'));
    }

    /**
     * Validasi & simpan data diri (step 1).
     * Mengembalikan JSON supaya bisa diproses oleh JS tanpa reload.
     */
    public function step1(OnboardingStep1Request $request): JsonResponse
    {
        // Simpan ke session
        session([
            'onboarding.nama'   => $request->nama,
            'onboarding.hp'     => $request->hp,
            'onboarding.email'  => $request->email,
            'onboarding.bidang' => $request->bidang,
            'onboarding.keluhan'=> $request->keluhan,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Simpan pilihan konsultan (step 2).
     */
    public function step2(Request $request): JsonResponse
    {
        $request->validate([
            'konsultan_id' => 'required|exists:konsultans,id',
        ]);

        session([
            'onboarding.konsultan_id' => $request->konsultan_id,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Simpan pilihan paket (step 3).
     */
    public function step3(Request $request): JsonResponse
    {
        $request->validate(['paket' => 'required|in:1,2,3']);

        session(['onboarding.paket' => $request->paket]);

        return response()->json(['success' => true]);
    }

    /**
     * Inisiasi pembayaran (step 4) dan kembalikan data ringkasan.
     */
    public function initPembayaran(Request $request): JsonResponse
    {
        $request->validate(['metode' => 'required|in:qris,bca,gopay,ovo']);

        $data = $this->service->initPembayaran(
            session('onboarding.konsultan_id'),
            (int) session('onboarding.paket'),
            $request->metode,
        );

        return response()->json(['success' => true, 'data' => $data]);
    }
}