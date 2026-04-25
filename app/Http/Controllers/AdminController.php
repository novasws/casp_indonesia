<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Konsultan;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\Pesan;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $stats = [
            'total_keluhan' => Keluhan::count(),
            'total_konsultasi' => Konsultasi::whereMonth('created_at', $month)
                                    ->whereYear('created_at', $year)
                                    ->when(!$user->is_superadmin, fn($q) => $q->where('konsultan_id', $user->id))
                                    ->count(),
            'total_konsultan' => Konsultan::count(),
            'total_pembayaran' => Pembayaran::where('status', 'lunas')
                                    ->whereMonth('created_at', $month)
                                    ->whereYear('created_at', $year)
                                    ->when(!$user->is_superadmin, fn($q) => $q->where('konsultan_id', $user->id))
                                    ->sum('total'),
            'aktif_konsultasi' => Konsultasi::where('status', 'aktif')
                                    ->when(!$user->is_superadmin, fn($q) => $q->where('konsultan_id', $user->id))
                                    ->count(),
            'pending_keluhan' => Keluhan::where('status', 'menunggu')->count(),
        ];

        // Mock recent activity if needed
        $recent_activities = [
            ['waktu' => '10 menit yang lalu', 'deskripsi' => 'Konsultasi #1024 selesai.', 'warna' => 'text-emerald-500'],
            ['waktu' => '35 menit yang lalu', 'deskripsi' => 'Pembayaran Rp 500,000 dari John Doe diterima.', 'warna' => 'text-emerald-500'],
            ['waktu' => '1 jam yang lalu', 'deskripsi' => 'Keluhan baru dari Jane Smith.', 'warna' => 'text-amber-500'],
            ['waktu' => '2 jam yang lalu', 'deskripsi' => 'Sistem mengirimkan notifikasi pengingat.', 'warna' => 'text-blue-500'],
        ];

        // Chart Data (Hanya dikumpulkan jika superadmin)
        $chart_data = null;
        $pie_data = null;

        if ($user->is_superadmin) {
            // Data Grafik Bar (7 Hari Terakhir)
            $last7Days = \Carbon\CarbonPeriod::create(now()->subDays(6)->startOfDay(), now()->endOfDay());
            $labels = [];
            $values = [];
            foreach ($last7Days as $date) {
                $labels[] = $date->format('d M');
                $values[] = Konsultasi::whereDate('created_at', $date->format('Y-m-d'))->count();
            }
            $chart_data = ['labels' => $labels, 'values' => $values];

            // Data Grafik Pie (Berdasarkan Paket)
            $paket1 = Konsultasi::where('paket', '1')->count();
            $paket2 = Konsultasi::where('paket', '2')->count();
            $paket3 = Konsultasi::where('paket', '3')->count();
            $pie_data = [$paket1, $paket2, $paket3];
        }

        // Hitung notifikasi antrean
        $pendingCount = 0;
        if (!$user->is_superadmin) {
            $pendingCount = Konsultasi::whereIn('status', ['menunggu', 'terjadwal'])
                ->where('konsultan_id', $user->id)
                ->count();
        }

        return view('admin.dashboard', compact('stats', 'recent_activities', 'chart_data', 'pie_data', 'month', 'year', 'pendingCount'));
    }

    public function keluhan()
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $keluhan = Keluhan::latest()->get();
        return view('admin.keluhan.index', compact('keluhan'));
    }

    public function keluhanChat($id)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        $keluhan = Keluhan::findOrFail($id);

        if ($keluhan->status === 'menunggu') {
            $keluhan->update(['status' => 'diproses']);
        }

        return view('admin.keluhan.chat', compact('keluhan'));
    }

    public function keluhanFetchPesan($id)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        $keluhan = Keluhan::findOrFail($id);

        $pesan = $keluhan->pesans()->oldest()->get()->map(function ($p) {
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
            'pesan'   => $pesan,
        ]);
    }

    public function keluhanReply(Request $request, $id)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        $request->validate(['isi' => 'required|string']);

        $keluhan = Keluhan::findOrFail($id);
        
        if ($keluhan->status === 'selesai') {
            return response()->json(['success' => false, 'message' => 'Chat sudah selesai.']);
        }

        $p = $keluhan->pesans()->create([
            'pengirim' => 'admin',
            'isi'      => $request->isi,
        ]);

        return response()->json([
            'success' => true,
            'pesan'   => [
                'id'       => $p->id,
                'pengirim' => $p->pengirim,
                'isi'      => $p->isi,
                'waktu'    => $p->waktu,
            ]
        ]);
    }

    public function keluhanSelesai($id)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        $keluhan = Keluhan::findOrFail($id);
        $keluhan->update(['status' => 'selesai']);

        return back()->with('success', 'Keluhan telah ditandai selesai.');
    }

    public function destroyKeluhan($id)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        $keluhan = Keluhan::findOrFail($id);
        $keluhan->pesans()->delete();
        $keluhan->delete();

        return redirect()->route('admin.keluhan.index')->with('success', 'Keluhan beserta riwayat chat berhasil dihapus.');
    }

    public function bulkDestroyKeluhan(Request $request)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:keluhans,id',
        ]);

        $keluhans = Keluhan::whereIn('id', $request->ids)->get();
        foreach ($keluhans as $keluhan) {
            $keluhan->pesans()->delete();
            $keluhan->delete();
        }

        return redirect()->route('admin.keluhan.index')->with('success', count($request->ids) . ' keluhan berhasil dihapus.');
    }

    public function konsultasi(Request $request)
    {
        $user = auth()->user();
        $query = Konsultasi::with('konsultan');
        
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $bulan = $request->input('bulan', 'semua');
        $tahun = $request->input('tahun', 'semua');

        if ($bulan != 'semua') {
            $query->whereMonth('created_at', $bulan);
        }
        if ($tahun != 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        if (!$user->is_superadmin) {
            $query->where('konsultan_id', $user->id);
        }

        $konsultasi = $query->latest()->get();
        // Return filter state back to view to keep selected option
        $currentStatus = $request->input('status', 'semua');
        $currentBulan = $bulan;
        $currentTahun = $tahun;

        // Hitung notifikasi antrean
        $pendingCount = 0;
        if (!$user->is_superadmin) {
            $pendingCount = Konsultasi::whereIn('status', ['menunggu', 'terjadwal'])
                ->where('konsultan_id', $user->id)
                ->count();
        }

        // Hitung urutan antrean per konsultan
        $queuePositions = [];
        $activeQueues = Konsultasi::whereIn('status', ['menunggu', 'terjadwal'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('konsultan_id');
            
        foreach($activeQueues as $kId => $queues) {
            foreach($queues as $index => $q) {
                $queuePositions[$q->id] = $index + 1;
            }
        }

        return view('admin.konsultasi.index', compact('konsultasi', 'currentStatus', 'currentBulan', 'currentTahun', 'pendingCount', 'queuePositions'));
    }

    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with('konsultasi');
        
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $bulan = $request->input('bulan', 'semua');
        $tahun = $request->input('tahun', date('Y')); // Default to current year

        if ($bulan != 'semua') {
            $query->whereMonth('created_at', $bulan);
        }
        if ($tahun != 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        $pembayaran = $query->latest()->get();
        $currentStatus = $request->input('status', 'semua');
        $currentBulan = $bulan;
        $currentTahun = $tahun;

        return view('admin.pembayaran.index', compact('pembayaran', 'currentStatus', 'currentBulan', 'currentTahun'));
    }

    public function exportPembayaran(Request $request)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        
        $fileName = 'Mutasi_Pembayaran_CASP_' . date('Y-m-d_H-i') . '.csv';
        
        $query = Pembayaran::with('konsultasi');
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $bulan = $request->input('bulan', 'semua');
        $tahun = $request->input('tahun', 'semua');

        if ($bulan != 'semua') {
            $query->whereMonth('created_at', $bulan);
        }
        if ($tahun != 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        $pembayarans = $query->latest()->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Order ID / Ref', 'Tanggal', 'Klien', 'Sesi Terkait', 'Metode', 'Total (Rp)', 'Status'];

        $callback = function() use($pembayarans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pembayarans as $item) {
                fputcsv($file, [
                    $item->order_id,
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->konsultasi ? $item->konsultasi->nama_klien : 'Data terhapus',
                    $item->konsultasi ? 'Sesi #' . $item->konsultasi_id : 'N/A',
                    $item->metode ?? 'Qris/Virtual Acc',
                    $item->total,
                    $item->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroyPembayaran($id)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }

    public function bulkDestroyPembayaran(Request $request)
    {
        if (!auth()->user()->is_superadmin) abort(403);
        
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:pembayarans,id',
        ]);

        Pembayaran::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', count($request->ids) . ' data pembayaran berhasil dihapus.');
    }

    public function konsultan()
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak. Anda bukan Superadmin.');
        }

        $konsultan = Konsultan::latest()->get();
        return view('admin.konsultan.index', compact('konsultan'));
    }

    public function storeKonsultan(Request $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak. Anda bukan Superadmin.');
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'gelar' => 'nullable|string|max:60',
            'spesialisasi' => 'required|string|max:80',
            'pengalaman_tahun' => 'required|integer|min:0',
            'username' => 'required|string|unique:konsultans,username|max:60',
            'password' => 'required|string|min:6',
        ]);

        $words = array_values(array_filter(array_map('trim', explode(' ', str_replace(['Dr.', 'S.H.', 'M.H.', 'M.Kn', ','], '', $request->nama)))));
        $inisial = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($request->nama, 0, 2));

        $colors = ['bg-blue-50 text-blue-700', 'bg-emerald-50 text-emerald-700', 'bg-amber-50 text-amber-700', 'bg-purple-50 text-purple-700', 'bg-rose-50 text-rose-700'];
        $warna = $colors[array_rand($colors)];

        Konsultan::create([
            'nama' => $request->nama,
            'gelar' => $request->gelar,
            'spesialisasi' => $request->spesialisasi,
            'pengalaman_tahun' => $request->pengalaman_tahun,
            'inisial' => $inisial,
            'warna_avatar' => explode(' ', $warna)[0],
            'status' => 'offline',
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'is_superadmin' => false,
        ]);

        return redirect()->back()->with('success', 'Konsultan baru berhasil ditambahkan.');
    }

    public function updateKonsultan(Request $request, $id)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak. Anda bukan Superadmin.');
        }

        $konsultan = Konsultan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'gelar' => 'nullable|string|max:60',
            'spesialisasi' => 'required|string|max:80',
            'pengalaman_tahun' => 'required|integer|min:0',
            'jadwal_shift' => 'nullable|string|max:100',
            'username' => 'required|string|max:60|unique:konsultans,username,' . $konsultan->id,
            'password' => 'nullable|string|min:6',
        ]);

        $words = array_values(array_filter(array_map('trim', explode(' ', str_replace(['Dr.', 'S.H.', 'M.H.', 'M.Kn', ','], '', $request->nama)))));
        $inisial = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($request->nama, 0, 2));

        $data = [
            'nama' => $request->nama,
            'gelar' => $request->gelar,
            'spesialisasi' => $request->spesialisasi,
            'pengalaman_tahun' => $request->pengalaman_tahun,
            'jadwal_shift' => $request->jadwal_shift,
            'inisial' => $inisial,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $konsultan->update($data);

        return redirect()->back()->with('success', 'Data konsultan berhasil diperbarui.');
    }

    public function destroyKonsultan($id)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak. Anda bukan Superadmin.');
        }

        $konsultan = Konsultan::findOrFail($id);
        
        // Prevent deleting superadmin
        if ($konsultan->is_superadmin) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Superadmin utama.');
        }
        
        $konsultan->delete();

        return redirect()->back()->with('success', 'Data konsultan berhasil dihapus.');
    }

    public function chat($id)
    {
        $user = auth()->user();
        $query = Konsultasi::with(['pesans', 'konsultan'])->where('id', $id);
        
        if (!$user->is_superadmin) {
            $query->where('konsultan_id', $user->id);
        }

        $konsultasi = $query->firstOrFail();
        return view('admin.konsultasi.chat', compact('konsultasi'));
    }

    public function reply(Request $request, $id)
    {
        $user = auth()->user();
        $konsultasi = Konsultasi::findOrFail($id);

        if ($user->is_superadmin && $konsultasi->konsultan_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda dalam mode audit dan tidak dapat mereply.'], 403);
        }

        $request->validate(['isi' => 'required|string']);

        $pesan = Pesan::create([
            'konsultasi_id' => $id,
            'pengirim' => 'konsultan', // Admin membalas sebagai konsultan
            'isi' => $request->isi,
        ]);

        return response()->json([
            'success' => true,
            'pesan' => [
                'id' => $pesan->id,
                'pengirim' => $pesan->pengirim,
                'isi' => $pesan->isi,
                'waktu' => $pesan->created_at->format('H.i')
            ]
        ]);
    }

    public function fetchPesan($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
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
            'pesans'  => $pesans
        ]);
    }

    public function akhiriSesi($id)
    {
        $user = auth()->user();
        $query = Konsultasi::where('id', $id);
        
        if (!$user->is_superadmin) {
            $query->where('konsultan_id', $user->id);
        }

        $konsultasi = $query->firstOrFail();
        $konsultasi->update(['status' => 'selesai']);

        // Mulai antrean berikutnya jika ada
        $nextQueue = Konsultasi::where('konsultan_id', $konsultasi->konsultan_id)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextQueue) {
            $nextQueue->update([
                'status' => 'aktif',
                'mulai_at' => now(),
            ]);
        }

        return redirect()->route('admin.konsultasi.index')->with('success', 'Sesi konsultasi telah berhasil diakhiri.');
    }

    public function mulaiSesi($id)
    {
        $user = auth()->user();
        $query = Konsultasi::where('id', $id);
        
        if (!$user->is_superadmin) {
            $query->where('konsultan_id', $user->id);
        }

        $konsultasi = $query->firstOrFail();

        if (in_array($konsultasi->status, ['terjadwal', 'menunggu'])) {
            $konsultasi->update([
                'status' => 'aktif',
                'mulai_at' => now(),
            ]);
            return redirect()->route('admin.konsultasi.chat', $id)->with('success', 'Sesi Konsultasi telah dimulai.');
        }

        return redirect()->route('admin.konsultasi.index')->with('error', 'Sesi tidak dapat dimulai karena statusnya tidak valid.');
    }

    public function destroyKonsultasi($id)
    {
        $user = auth()->user();
        if (!$user->is_superadmin) {
            abort(403, 'Akses ditolak.');
        }

        $konsultasi = Konsultasi::findOrFail($id);
        // Hapus pesan terkait terlebih dahulu
        $konsultasi->pesans()->delete();
        // Hapus konsultasi
        $konsultasi->delete();

        return redirect()->route('admin.konsultasi.index')->with('success', 'Data konsultasi beserta riwayat chat berhasil dihapus.');
    }

    public function bulkDestroyKonsultasi(Request $request)
    {
        $user = auth()->user();
        if (!$user->is_superadmin) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:konsultasis,id',
        ]);

        $konsultasis = Konsultasi::whereIn('id', $request->ids)->get();

        foreach ($konsultasis as $konsultasi) {
            $konsultasi->pesans()->delete();
            $konsultasi->delete();
        }

        $count = count($request->ids);
        return redirect()->route('admin.konsultasi.index')->with('success', "{$count} data konsultasi beserta riwayat chat berhasil dihapus.");
    }

    public function notifications()
    {
        $user = auth()->user();
        
        $aktif = Konsultasi::where('status', 'aktif');
        if (!$user->is_superadmin) {
            $aktif->where('konsultan_id', $user->id);
        }
        $aktif_count = $aktif->count();

        return response()->json([
            'count' => $aktif_count,
            'message' => $aktif_count > 0 ? "$aktif_count Konsultasi Aktif" : ""
        ]);
    }

    // ========== KONTEN MANAGEMENT ==========

    public function konten()
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak.');
        }

        $allContents = SiteContent::all();
        $contents = [];
        foreach ($allContents as $item) {
            if ($item->type === 'json') {
                $contents[$item->key] = json_decode($item->value, true);
            } else {
                $contents[$item->key] = $item->value;
            }
        }

        return view('admin.konten.index', compact('contents'));
    }

    public function updateKonten(Request $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak.');
        }

        $fields = ['hero_badge', 'hero_judul', 'hero_deskripsi', 'stat_kasus_selesai', 'stat_kepuasan', 'stat_konsultan', 'stat_harga_mulai', 'konsultan_quote'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                SiteContent::setValue($field, $request->input($field));
            }
        }

        return redirect()->route('admin.konten.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function updateLayanan(Request $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak.');
        }

        $layananInput = $request->input('layanan', []);
        $layanan = [];

        foreach ($layananInput as $item) {
            $contohKasus = [];
            if (!empty($item['contoh_kasus_text'])) {
                $contohKasus = array_values(array_filter(array_map('trim', explode("\n", $item['contoh_kasus_text']))));
            }

            $layanan[] = [
                'icon' => $item['icon'] ?? '⚖️',
                'judul' => $item['judul'] ?? '',
                'deskripsi' => $item['deskripsi'] ?? '',
                'konten_lengkap' => $item['konten_lengkap'] ?? '',
                'contoh_kasus' => $contohKasus,
            ];
        }

        SiteContent::setValue('layanan', $layanan);

        return redirect()->route('admin.konten.index')->with('success', 'Data layanan berhasil diperbarui!');
    }

    public function updateCaraKerja(Request $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Akses ditolak.');
        }

        $caraKerjaInput = $request->input('cara_kerja', []);
        $caraKerja = [];

        foreach ($caraKerjaInput as $item) {
            $caraKerja[] = [
                'num' => (int) ($item['num'] ?? count($caraKerja) + 1),
                'judul' => $item['judul'] ?? '',
                'desc' => $item['desc'] ?? '',
            ];
        }

        SiteContent::setValue('cara_kerja', $caraKerja);

        return redirect()->route('admin.konten.index')->with('success', 'Alur konsultasi berhasil diperbarui!');
    }

    // ========== PROFIL MANAGEMENT ==========

    public function editProfil()
    {
        $user = auth()->user();
        return view('admin.profil.edit', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama'             => 'required|string|max:100',
            'gelar'            => 'nullable|string|max:60',
            'spesialisasi'     => 'required|string|max:80',
            'pengalaman_tahun' => 'required|integer|min:0|max:50',
            'jadwal_shift'     => 'nullable|string|max:100',
            'bio'              => 'nullable|string|max:3000',
            'quote'            => 'nullable|string|max:1000',
            'username'         => 'required|string|max:60|unique:konsultans,username,' . $user->id,
            'password'         => 'nullable|string|min:6|confirmed',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'nama'             => $request->nama,
            'gelar'            => $request->gelar,
            'spesialisasi'     => $request->spesialisasi,
            'pengalaman_tahun' => $request->pengalaman_tahun,
            'jadwal_shift'     => $request->jadwal_shift,
            'bio'              => $request->bio,
            'quote'            => $request->quote,
            'username'         => $request->username,
        ];

        if ($request->hasFile('foto')) {
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('profil', 'public');
        }

        $user->update($data);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->route('admin.profil.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
