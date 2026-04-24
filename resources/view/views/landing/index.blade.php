@extends('layouts.app')

@section('title', 'CASP Indonesia – Konsultasi Hukum Online')

@push('styles')
<style>
/* HERO */
.hero{background:linear-gradient(135deg,var(--blue-900) 0%,var(--blue-700) 55%,#1E3A8A 100%);color:white;padding:72px 2rem 80px;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='1' fill='%23FFFFFF' fill-opacity='0.04'/%3E%3C/svg%3E");pointer-events:none}
.hero-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:5px 14px;font-size:.78rem;font-weight:500;margin-bottom:20px}
.hero-badge::before{content:'●';color:#4ADE80;font-size:.6rem}
.hero h1{font-size:clamp(2rem,5vw,3.2rem);line-height:1.18;max-width:660px;margin-bottom:18px}
.hero h1 em{font-style:italic;color:var(--blue-300)}
.hero p{font-size:1.05rem;color:#CBD5E1;max-width:540px;line-height:1.65;margin-bottom:36px;font-weight:300}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:white;color:var(--blue-700);font-weight:600;font-size:.95rem;padding:13px 28px;border-radius:8px;border:none;cursor:pointer;transition:transform .15s,box-shadow .15s;text-decoration:none}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(0,0,0,.2)}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);color:white;font-weight:500;font-size:.9rem;padding:12px 24px;border-radius:8px;border:1px solid rgba(255,255,255,.25);cursor:pointer;transition:background .15s;margin-left:12px;text-decoration:none}
.btn-secondary:hover{background:rgba(255,255,255,.18)}
.hero-stats{display:flex;gap:36px;margin-top:48px;flex-wrap:wrap}
.stat-item .num{font-family:'DM Serif Display',serif;font-size:2rem;color:white}
.stat-item .lbl{font-size:.78rem;color:#94A3B8;margin-top:2px;font-weight:400}
/* LAYANAN */
.section{padding:64px 2rem;max-width:1100px;margin:0 auto}
.section-label{font-size:.75rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--blue-500);margin-bottom:10px}
.section h2{font-size:clamp(1.6rem,3.5vw,2.2rem);color:var(--blue-900);margin-bottom:12px}
.section > p{color:var(--gray-600);font-size:1rem;line-height:1.65;margin-bottom:40px;max-width:560px}
.card-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px}
.layanan-card{background:white;border:1px solid var(--gray-200);border-radius:14px;padding:28px 24px;cursor:pointer;transition:border-color .2s,box-shadow .2s,transform .2s;text-decoration:none;display:block}
.layanan-card:hover{border-color:var(--blue-400);box-shadow:0 8px 32px rgba(37,99,235,.15);transform:translateY(-2px)}
.card-icon{width:46px;height:46px;background:var(--blue-50);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:1.3rem}
.card-title{font-weight:600;font-size:1rem;color:var(--blue-900);margin-bottom:8px}
.card-desc{font-size:.85rem;color:var(--gray-600);line-height:1.6}
.card-arrow{display:flex;align-items:center;gap:4px;font-size:.8rem;font-weight:600;color:var(--blue-500);margin-top:16px}
/* HOW IT WORKS */
.how-section{background:var(--blue-900);color:white;padding:64px 2rem}
.how-inner{max-width:1000px;margin:0 auto;text-align:center}
.how-inner .section-label{color:var(--blue-300)}
.how-inner h2{color:white;margin-bottom:12px}
.how-inner > p{color:#94A3B8;max-width:500px;margin:0 auto 48px}
.steps-row{display:flex;align-items:flex-start;gap:0;position:relative}
.steps-row::before{content:'';position:absolute;top:24px;left:10%;right:10%;height:1px;background:rgba(255,255,255,.12);z-index:0}
.step-item{flex:1;text-align:center;padding:0 12px;position:relative;z-index:1}
.step-num{width:48px;height:48px;border-radius:50%;background:var(--blue-500);color:white;font-weight:700;font-size:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.step-item h4{font-size:.95rem;font-weight:600;color:white;margin-bottom:6px}
.step-item p{font-size:.78rem;color:#94A3B8;line-height:1.5}
/* FORM KELUHAN */
.complaint-section{background:linear-gradient(180deg,var(--blue-50) 0%,white 100%);padding:56px 2rem}
.complaint-inner{max-width:760px;margin:0 auto}
.form-card{background:white;border:1px solid var(--gray-200);border-radius:16px;padding:36px 40px;box-shadow:0 4px 24px rgba(0,0,0,.06)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:.82rem;font-weight:600;color:var(--gray-600);margin-bottom:6px;letter-spacing:.02em}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:11px 14px;border:1px solid var(--gray-200);border-radius:8px;font-size:.9rem;font-family:'DM Sans',sans-serif;color:var(--gray-900);transition:border-color .15s,box-shadow .15s;background:var(--gray-50)}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--blue-400);box-shadow:0 0 0 3px rgba(59,130,246,.1);background:white}
.form-group textarea{resize:vertical;min-height:100px}
.form-check{display:flex;align-items:flex-start;gap:10px;margin-bottom:20px}
.form-check input[type=checkbox]{width:16px;height:16px;margin-top:2px;accent-color:var(--blue-500);cursor:pointer}
.form-check label{font-size:.83rem;color:var(--gray-600);line-height:1.5;cursor:pointer}
.form-check a{color:var(--blue-500);font-weight:500}
.btn-submit{width:100%;padding:14px;background:var(--blue-600);color:white;font-weight:600;font-size:.95rem;border:none;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .15s,transform .1s}
.btn-submit:hover{background:var(--blue-700);transform:translateY(-1px)}
.btn-submit:active{transform:scale(.99)}
@media(max-width:600px){
    .form-row{grid-template-columns:1fr}
    .steps-row{flex-direction:column;align-items:center;gap:24px}
    .steps-row::before{display:none}
    .form-card{padding:24px 20px}
}
</style>
@endpush

@section('content')
    {{-- NAVBAR --}}
    @include('partials.navbar', ['navType' => 'landing'])

    {{-- ===== HERO ===== --}}
    <section class="hero">
        <div style="max-width:1100px;margin:0 auto">
            <div class="hero-badge">Konsultan hukum bersertifikat · Online 24/7</div>
            <h1>Solusi Hukum <em>Terpercaya</em><br/>untuk Setiap Kebutuhan</h1>
            <p>Konsultasi hukum langsung dengan pakar berpengalaman. Privat, aman, dan terjangkau – tanpa perlu antri di kantor pengacara.</p>
            <div>
                <a href="{{ route('onboarding.index') }}" class="btn-primary">
                    Mulai Konsultasi
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="#complaint-form" class="btn-secondary">Ajukan Keluhan Dulu</a>
            </div>
            <div class="hero-stats">
                @foreach($stats as $key => $val)
                    <div class="stat-item">
                        <div class="num">{{ $val }}</div>
                        <div class="lbl">
                            @if($key === 'kasus_selesai') Kasus diselesaikan
                            @elseif($key === 'kepuasan') Tingkat kepuasan
                            @elseif($key === 'konsultan') Konsultan aktif
                            @else Mulai dari
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== LAYANAN ===== --}}
    <div class="section">
        <div class="section-label">Bidang Layanan</div>
        <h2>Pilih Jenis Konsultasi Hukum</h2>
        <p>Semua konsultan kami tersertifikasi dan berpengalaman di bidangnya masing-masing.</p>
        <div class="card-grid">
            @foreach($layanan as $item)
            <a href="{{ route('onboarding.index') }}" class="layanan-card">
                <div class="card-icon">{{ $item['icon'] }}</div>
                <div class="card-title">{{ $item['judul'] }}</div>
                <div class="card-desc">{{ $item['deskripsi'] }}</div>
                <div class="card-arrow">Konsultasi <span>→</span></div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ===== CARA KERJA ===== --}}
    <section class="how-section">
        <div class="how-inner">
            <div class="section-label">Cara Kerja</div>
            <h2 class="serif" style="font-size:2rem;margin-bottom:12px">Konsultasi dalam 5 Langkah</h2>
            <p>Proses mudah dan cepat – dari isi data hingga chat dengan konsultan.</p>
            <div class="steps-row">
                @foreach($cara_kerja as $step)
                <div class="step-item">
                    <div class="step-num">{{ $step['num'] }}</div>
                    <h4>{{ $step['judul'] }}</h4>
                    <p>{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== FORM KELUHAN ===== --}}
    <section class="complaint-section" id="complaint-form">
        <div class="complaint-inner">
            <div style="text-align:center;margin-bottom:36px">
                <div class="section-label" style="text-align:center">Form Keluhan</div>
                <h2 class="serif" style="color:var(--blue-900);font-size:2rem">Ajukan Keluhan Anda</h2>
                <p style="color:var(--gray-600);margin-top:8px;font-size:.9rem">Tim kami akan merespons dalam 1×24 jam. Keluhan Anda kami jaga kerahasiaannya.</p>
            </div>
            <div class="form-card">
                <div id="keluhan-success-msg" style="display:none;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:14px 16px;margin-bottom:20px;font-size:.85rem;color:#166534;text-align:center">
                    ✅ Keluhan Anda berhasil dikirim! Tim kami akan menghubungi Anda segera.
                    <br/><br/>
                    <a href="{{ route('onboarding.index') }}" style="background:var(--blue-600);color:white;border:none;border-radius:6px;padding:9px 20px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-block">Lanjut Konsultasi Sekarang →</a>
                </div>

                <form id="keluhan-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nama" placeholder="Masukkan nama lengkap" required/>
                        </div>
                        <div class="form-group">
                            <label>Nomor HP *</label>
                            <input type="tel" name="hp" placeholder="08xxxxxxxxxx" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Email *</label>
                        <input type="email" name="email" placeholder="email@contoh.com" required/>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kategori Keluhan *</label>
                            <select name="kategori" required>
                                <option value="">-- Pilih kategori --</option>
                                <option>Hukum Perdata</option>
                                <option>Hukum Keluarga</option>
                                <option>Hukum Bisnis</option>
                                <option>Hukum Properti</option>
                                <option>Hukum Ketenagakerjaan</option>
                                <option>Hukum Pidana</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tingkat Urgensi</label>
                            <select name="urgensi">
                                <option>Normal (1-3 hari)</option>
                                <option>Mendesak (hari ini)</option>
                                <option>Sangat Mendesak (segera)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Uraian Keluhan / Permasalahan *</label>
                        <textarea name="isi" placeholder="Ceritakan permasalahan hukum Anda secara singkat. Semakin detail, semakin cepat kami mencocokkan Anda dengan konsultan yang tepat..." required></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="keluhan-agree" required/>
                        <label for="keluhan-agree">Saya menyetujui <a href="#">syarat & ketentuan</a> dan <a href="#">kebijakan privasi</a> CASP Indonesia. Data keluhan dijaga kerahasiaannya.</label>
                    </div>
                    <div id="keluhan-error" style="display:none;background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;color:#991B1B"></div>
                    <button type="submit" class="btn-submit" id="keluhan-btn">Kirim Keluhan →</button>
                </form>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    @include('partials.footer')
@endsection

@push('scripts')
<script>
document.getElementById('keluhan-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn     = document.getElementById('keluhan-btn');
    const errBox  = document.getElementById('keluhan-error');
    const agree   = document.getElementById('keluhan-agree');

    if (!agree.checked) {
        errBox.textContent = 'Harap setujui syarat & ketentuan terlebih dahulu.';
        errBox.style.display = 'block';
        return;
    }

    btn.textContent = 'Mengirim...';
    btn.disabled    = true;
    errBox.style.display = 'none';

    try {
        const formData = new FormData(this);
        const res  = await fetch('{{ route('keluhan.store') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData,
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('keluhan-success-msg').style.display = 'block';
            this.style.display = 'none';
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Terjadi kesalahan.');
            errBox.textContent = errors;
            errBox.style.display = 'block';
            btn.textContent = 'Kirim Keluhan →';
            btn.disabled = false;
        }
    } catch (err) {
        errBox.textContent = 'Gagal mengirim keluhan. Silakan coba lagi.';
        errBox.style.display = 'block';
        btn.textContent = 'Kirim Keluhan →';
        btn.disabled = false;
    }
});
</script>
@endpush