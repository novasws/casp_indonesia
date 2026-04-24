<?php

namespace App\Http\Controllers;

use App\Events\KeluhanDikirim;
use App\Http\Requests\KeluhanRequest;
use App\Models\Keluhan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    /**
     * Simpan keluhan yang dikirim dari form landing page.
     */
    public function start(KeluhanRequest $request): JsonResponse
    {
        $token = \Illuminate\Support\Str::random(40);
        $keluhan = Keluhan::create([
            'nama'       => $request->nama,
            'hp'         => $request->hp,
            'email'      => $request->email,
            'kategori'   => $request->kategori,
            'urgensi'    => $request->urgensi,
            'isi'        => 'Dipersingkat dalam chat pertama',
            'status'     => 'menunggu',
            'token_sesi' => $token,
        ]);

        $keluhan->pesans()->create([
            'pengirim' => 'klien',
            'isi'      => $request->isi,
        ]);

        event(new KeluhanDikirim($keluhan));

        return response()->json([
            'success' => true,
            'token'   => $token,
            'message' => 'Sesi chat CS dimulai.',
        ]);
    }

    public function fetch($token): JsonResponse
    {
        $keluhan = Keluhan::where('token_sesi', $token)->first();
        if (!$keluhan) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid'], 404);
        }

        $pesans = $keluhan->pesans()->oldest()->get()->map(function ($p) {
            return [
                'id'       => $p->id,
                'pengirim' => $p->pengirim,
                'isi'      => $p->isi,
                'waktu'    => $p->waktu,
            ];
        });

        return response()->json([
            'success' => true,
            'status'  => $keluhan->status,
            'pesan'   => $pesans
        ]);
    }

    public function send(Request $request, $token): JsonResponse
    {
        $request->validate(['isi' => 'required|string']);

        $keluhan = Keluhan::where('token_sesi', $token)->first();
        if (!$keluhan) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid'], 404);
        }

        if ($keluhan->status === 'selesai') {
            return response()->json(['success' => false, 'message' => 'Sesi chat telah ditutup.']);
        }

        $pesan = $keluhan->pesans()->create([
            'pengirim' => 'klien',
            'isi'      => $request->isi,
        ]);

        return response()->json([
            'success' => true,
            'pesan'   => [
                'id'       => $pesan->id,
                'pengirim' => $pesan->pengirim,
                'isi'      => $pesan->isi,
                'waktu'    => $pesan->waktu,
            ]
        ]);
    }
}