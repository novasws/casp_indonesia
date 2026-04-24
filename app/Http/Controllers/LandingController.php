<?php

namespace App\Http\Controllers;

use App\Models\Konsultan;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    private function getLayananData(): array
    {
        // Try database first, fallback to hardcoded
        $dbLayanan = SiteContent::getValue('layanan');
        if (!empty($dbLayanan) && is_array($dbLayanan)) {
            return $dbLayanan;
        }

        return [
            [
                'icon' => '⚖️', 
                'judul' => 'Hukum Perdata',          
                'deskripsi' => 'Sengketa kontrak, wanprestasi, hutang-piutang, dan keperdataan umum.',
                'konten_lengkap' => 'Hukum Perdata di Indonesia mengatur hubungan antar individu maupun badan usaha yang menitikberatkan pada pemenuhan hak dan kewajiban perorangan. Tim konsultan kami ahli dalam merancang strategi penyelesaian sengketa melalui jalur damai (mediasi) yang efisien, maupun dengan ketegasan melalui peradilan (litigasi) jika diperlukan untuk menyelamatkan aset Anda.',
                'contoh_kasus' => [
                    'Sengketa wanprestasi (pelanggaran/ingkar janji kontrak)',
                    'Penyelesaian kredit macet & penagihan hutang-piutang',
                    'Gugatan Perbuatan Melawan Hukum (PMH) & ganti rugi',
                    'Sengketa sengketa kepemilikan aset tak bergerak'
                ]
            ],
            [
                'icon' => '👨‍👩‍👧', 
                'judul' => 'Hukum Keluarga',         
                'deskripsi' => 'Perceraian, hak asuh anak, waris, perkawinan, dan adopsi.',
                'konten_lengkap' => 'Hukum Keluarga menangani aspek fundamental dari ikatan personal Anda. Kami memahami bahwa urusan dalam ranah keluarga membutuhkan lebih dari sekadar pemahaman undang-undang; ia membutuhkan empati, diskresi mutlak, dan penyelesaian yang meminimalisir dampak emosional, khususnya yang melibatkan anak-anak di bawah umur.',
                'contoh_kasus' => [
                    'Proses perceraian di Pengadilan Agama dan Pengadilan Negeri',
                    'Perebutan status hak asuh anak (Hadhanah) & nafkah',
                    'Pembagian Harta Gono Gini secara seimbang',
                    'Penetapan hak waris, surat wasiat, hingga proses adopsi sah'
                ]
            ],
            [
                'icon' => '🏢', 
                'judul' => 'Hukum Bisnis',            
                'deskripsi' => 'Pendirian PT, kontrak kerja sama, M&A, kepatuhan regulasi bisnis.',
                'konten_lengkap' => 'Di era komersial yang serba cepat, langkah bisnis tanpa landasan hukum adalah risiko tinggi. Kami menyediakan pendampingan legal end-to-end bagi startup, UMKM, maupun korporasi multinasional, guna memastikan setiap investasi terlindungi, struktur perusahaan sehat, dan terhindar dari potensi tuntutan kepatuhan di kemudian hari.',
                'contoh_kasus' => [
                    'Merger, Akuisisi (M&A), dan Penanaman Modal (FDI)',
                    'Drafting & Review Kontrak Komersial, B2B, Vendor',
                    'Pendaftaran Hak Kekayaan Intelektual (Paten, Merek, Cipta)',
                    'Kepatuhan regulasi lisensi, PKPU, dan perlindungan kepailitan'
                ]
            ],
            [
                'icon' => '🏠', 
                'judul' => 'Hukum Properti',         
                'deskripsi' => 'Jual beli tanah/rumah, sertifikat, sengketa lahan, PPJB dan AJB.',
                'konten_lengkap' => 'Properti dan agraria merupakan instrumen investasi dengan valuasi raksasa yang amat rentan akan tumpah tindih zonasi dan pemalsuan dokumen. Kami hadir untuk menavigasi administrasi ruwet di BPN, mendampingi proses due-diligence aset, memvalidasi legalitas fisik, dan menengahi okupasi lahan tanpa hak penuh kapabilitas.',
                'contoh_kasus' => [
                    'Sengketa perbatasan tanah, sertifikat ganda, dan sengketa waris tanah',
                    'Pembuatan perikatan jual beli (PPJB) dan Akta Jual Beli (AJB) yang aman',
                    'Perkara sengketa antara developer perumahan dan konsumen properti',
                    'Sewa-menyewa gedung komersial, pabrik, dan perizinan tata ruang'
                ]
            ],
            [
                'icon' => '💼', 
                'judul' => 'Hukum Ketenagakerjaan',  
                'deskripsi' => 'PHK, pesangon, kontrak kerja, hak karyawan dan pengusaha.',
                'konten_lengkap' => 'Dinamika antara perusahaan (Pemberi Kerja) dan karyawan menuntut keseimbangan hak berdasarkan UU Cipta Kerja terkini. Kami mewakili kedua sisi — secara adil membantu pekerja mendapatkan hak finansialnya setelah diberhentikan secara sepihak, sekaligus mendampingi perusahaan lolos dari potensi mogok massal melalui kontrak kerja kedap celah.',
                'contoh_kasus' => [
                    'Negosiasi Bipartit dan Tripartit Pemutusan Hubungan Kerja (PHK)',
                    'Sengketa penghitungan kompensasi Pesangon yang tidak sesuai norma',
                    'Penyusunan Peraturan Perusahaan (PP) dan Perjanjian Kerja Bersama (PKB)',
                    'Audit ketenagakerjaan dan pendampingan sengketa karyawan ekspatriat'
                ]
            ],
            [
                'icon' => '🔒', 
                'judul' => 'Hukum Pidana',           
                'deskripsi' => 'Pendampingan kasus pidana, laporan polisi, pembuatan SKCK, dan konsultasi.',
                'konten_lengkap' => 'Kebebasan adalah hak asasi terpenting. Ketika Anda berhadapan dengan tuduhan pidana, setiap kalimat di hadapan pihak berwajib dapat menentukan masa depan Anda. Sebagai kuasa hukum, tim kami memberikan pembelaan investigatif yang agresif, mengawal penyidikan secara melekat, memastikan hak terperiksa/tersangka dipenuhi sesuai standar KUHAP.',
                'contoh_kasus' => [
                    'Tindak pidana korupsi, cybercrime & kejahatan perbankan (White collar)',
                    'Pendampingan pemeriksaan Tersangka di Kepolisian hingga Persidangan',
                    'Tuduhan pencemaran nama baik (UU ITE), penipuan, dan penggelapan',
                    'Penyelesaian dan mediasi Restorative Justice sesuai dengan KUHP Baru'
                ]
            ],
        ];
    }

    /**
     * Tampilkan halaman utama (landing page).
     */
    public function index(): \Illuminate\View\View
    {
        $stats = [
            'kasus_selesai' => SiteContent::getValue('stat_kasus_selesai', '2.400+'),
            'kepuasan'      => SiteContent::getValue('stat_kepuasan', '98%'),
            'konsultan'     => SiteContent::getValue('stat_konsultan', '35+'),
            'harga_mulai'   => SiteContent::getValue('stat_harga_mulai', 'Rp 50rb'),
        ];

        $layanan = $this->getLayananData();

        $dbCaraKerja = SiteContent::getValue('cara_kerja');
        $cara_kerja = !empty($dbCaraKerja) && is_array($dbCaraKerja) ? $dbCaraKerja : [
            ['num' => 1, 'judul' => 'Isi Data Diri',    'desc' => 'Nama, nomor HP, dan alamat email Anda'],
            ['num' => 2, 'judul' => 'Pilih Konsultan',  'desc' => 'Sesuai bidang dan ketersediaan'],
            ['num' => 3, 'judul' => 'Pilih Paket',      'desc' => 'Durasi 1, 2, atau 3 jam'],
            ['num' => 4, 'judul' => 'Pembayaran',       'desc' => 'QRIS, Transfer BCA, GoPay, OVO'],
            ['num' => 5, 'judul' => 'Konsultasi Chat',  'desc' => 'Chat langsung + unduh transkrip'],
        ];

        $konsultan = Konsultan::where('is_superadmin', false)->get();

        // Hero content from database
        $hero = [
            'badge' => SiteContent::getValue('hero_badge', 'Konsultan hukum bersertifikat · Online 24/7'),
            'judul' => SiteContent::getValue('hero_judul', 'Hallo CASP'),
            'deskripsi' => SiteContent::getValue('hero_deskripsi', 'Pusat konsultasi kebijakan ruang angkasa, udara, dan layanan hukum terpadu. Terjamin aman, privat, dan profesional bersama pakar berpengalaman di bidangnya.'),
        ];

        $konsultan_quote = SiteContent::getValue('konsultan_quote', 'Sebagai praktisi hukum dengan track record penyelesaian kasus di atas 98%, prinsip utama saya adalah memberikan perlindungan maksimal terhadap hak-hak hukum Anda secara transparan dan berintegritas.');

        return view('landing.index', compact('stats', 'layanan', 'cara_kerja', 'konsultan', 'hero', 'konsultan_quote'));
    }

    /**
     * Tampilkan halaman detail layanan spesifik berdasarkan slug.
     */
    public function layananDetail($slug): \Illuminate\View\View
    {
        $semuaLayanan = $this->getLayananData();
        $layanan = null;

        foreach ($semuaLayanan as $item) {
            if (\Illuminate\Support\Str::slug($item['judul']) === $slug) {
                $layanan = $item;
                break;
            }
        }

        if (!$layanan) {
            abort(404, 'Layanan tidak ditemukan.');
        }

        return view('landing.layanan-detail', compact('layanan'));
    }

    public function lacakSesi()
    {
        return view('landing.lacak-sesi');
    }

    public function prosesLacakSesi(Request $request)
    {
        $request->validate([
            'token_sesi' => 'required|string',
            'no_hp' => 'required|string',
        ]);

        $konsultasi = \App\Models\Konsultasi::where('token_sesi', $request->token_sesi)
            ->where('klien_hp', $request->no_hp)
            ->first();

        if (!$konsultasi) {
            return back()->with('error', 'Data sesi tidak ditemukan atau nomor HP tidak cocok.');
        }

        return redirect()->route('chat.index', $konsultasi->id);
    }
}