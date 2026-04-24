@extends('layouts.app')

@section('title', 'Mulai Konsultasi – CASP Indonesia')

@push('styles')
<style>
.onboard-wrap{min-height:calc(100vh - 60px);background:var(--gray-50);display:flex;flex-direction:column}
.progress-bar{background:white;border-bottom:1px solid var(--gray-200);padding:16px 32px}
.progress-steps{display:flex;align-items:center;gap:0;max-width:700px;margin:0 auto}
.prog-step{display:flex;align-items:center;gap:8px;flex:1}
.prog-step:not(:last-child)::after{content:'';flex:1;height:1px;background:var(--gray-200);margin:0 8px}
.prog-circle{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;border:2px solid var(--gray-200);color:var(--gray-400);background:white;transition:all .3s}
.prog-circle.done{background:var(--blue-600);border-color:var(--blue-600);color:white}
.prog-circle.active{background:var(--blue-900);border-color:var(--blue-900);color:white}
.prog-label{font-size:.75rem;font-weight:500;color:var(--gray-400)}
.prog-label.active{color:var(--blue-900);font-weight:600}
.onboard-body{flex:1;padding:40px 2rem;display:flex;justify-content:center}
.onboard-card{background:white;border:1px solid var(--gray-200);border-radius:16px;padding:40px;width:100%;max-width:680px;box-shadow:0 4px 20px rgba(0,0,0,.06)}
.onboard-card h2{font-family:'DM Serif Display',serif;font-size:1.6rem;color:var(--blue-900);margin-bottom:6px}
.onboard-card > p{color:var(--gray-600);font-size:.88rem;margin-bottom:28px}
/* Agents */
.agent-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px}
.agent-card{border:2px solid var(--gray-200);border-radius:12px;padding:22px 16px;text-align:center;cursor:pointer;transition:border-color .2s,box-shadow .2s}
.agent-card.selected{border-color:var(--blue-600);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
.agent-avatar{width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-weight:700;font-size:1rem}
.av-blue   {background:#DBEAFE;color:#1D4ED8}
.av-indigo {background:#E0E7FF;color:#3730A3}
.av-green  {background:#F0FDF4;color:#166534}
.av-orange {background:#FFF7ED;color:#C2410C}
.av-purple {background:#F5F3FF;color:#5B21B6}
.av-red    {background:#FFF1F2;color:#9F1239}
.agent-online{display:inline-flex;align-items:center;gap:4px;font-size:.7rem;color:#16A34A;font-weight:500;margin-bottom:8px}
.agent-online::before{content:'●';font-size:.55rem}
.agent-sibuk{display:inline-flex;align-items:center;gap:4px;font-size:.7rem;color:#F59E0B;font-weight:500;margin-bottom:8px}
.agent-sibuk::before{content:'●';font-size:.55rem}
.agent-name{font-weight:600;font-size:.88rem;color:var(--blue-900);margin-bottom:2px}
.agent-spec{font-size:.75rem;color:var(--gray-600)}
.agent-exp{font-size:.72rem;color:var(--gray-400);margin-top:4px}
/* Paket */
.paket-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px}
.paket-card{border:2px solid var(--gray-200);border-radius:12px;padding:24px 18px;cursor:pointer;transition:border-color .2s,box-shadow .2s;position:relative}
.paket-card.selected{border-color:var(--blue-600);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
.paket-badge{position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:var(--blue-900);color:white;font-size:.68rem;font-weight:700;padding:3px 12px;border-radius:100px;white-space:nowrap}
.paket-dur{font-family:'DM Serif Display',serif;font-size:1.4rem;color:var(--blue-900);margin-bottom:2px}
.paket-price{font-size:1.15rem;font-weight:700;color:var(--blue-600);margin-bottom:14px}
.paket-feat{list-style:none;font-size:.78rem;color:var(--gray-600);line-height:1.8}
.paket-feat li::before{content:'✓ ';color:var(--blue-500);font-weight:700}
.paket-feat li.disabled{color:#CBD5E1;text-decoration:line-through}
.paket-feat li.disabled::before{color:#CBD5E1}
/* Payment */
.pay-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px}
.pay-methods{display:flex;flex-direction:column;gap:10px}
.pay-method{border:2px solid var(--gray-200);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px;cursor:pointer;transition:border-color .15s}
.pay-method.selected{border-color:var(--blue-600);background:var(--blue-50)}
.pay-method-radio{width:18px;height:18px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:border-color .15s}
.pay-method.selected .pay-method-radio{border-color:var(--blue-600)}
.pay-dot{width:8px;height:8px;border-radius:50%;background:var(--blue-600);display:none}
.pay-method.selected .pay-dot{display:block}
.pay-method-label{font-size:.88rem;font-weight:600;color:var(--gray-900)}
.pay-method-sub{font-size:.75rem;color:var(--gray-400)}
.pay-summary{background:var(--gray-50);border:1px solid var(--gray-200);border-radius:12px;padding:22px}
.pay-row{display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:10px;color:var(--gray-600)}
.pay-row.total{font-weight:700;color:var(--gray-900);font-size:.95rem;border-top:1px solid var(--gray-200);padding-top:12px;margin-top:4px}
.pay-timer{text-align:center;font-size:.8rem;color:#64748B;margin-top:12px}
.pay-timer span{color:#DC2626;font-weight:700}
.qr-box{background:white;border:1px solid var(--gray-200);border-radius:8px;padding:16px;text-align:center;margin-top:16px}
.qr-placeholder{width:100px;height:100px;background:var(--gray-100);border-radius:6px;margin:0 auto 8px;display:flex;align-items:center;justify-content:center}
/* Form */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:.82rem;font-weight:600;color:var(--gray-600);margin-bottom:6px;letter-spacing:.02em}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:11px 14px;border:1px solid var(--gray-200);border-radius:8px;font-size:.9rem;font-family:'DM Sans',sans-serif;color:var(--gray-900);transition:border-color .15s,box-shadow .15s;background:var(--gray-50)}
.form-group input:focus,.form-group select:focus{outline:none;border-color:var(--blue-400);box-shadow:0 0 0 3px rgba(59,130,246,.1);background:white}
.form-check{display:flex;align-items:flex-start;gap:10px;margin-bottom:20px}
.form-check input[type=checkbox]{width:16px;height:16px;margin-top:2px;accent-color:var(--blue-500);cursor:pointer}
.form-check label{font-size:.83rem;color:var(--gray-600);line-height:1.5;cursor:pointer}
.form-check a{color:var(--blue-500);font-weight:500}
.btn-submit{width:100%;padding:14px;background:var(--blue-600);color:white;font-weight:600;font-size:.95rem;border:none;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .15s,transform .1s}
.btn-submit:hover{background:var(--blue-700);transform:translateY(-1px)}
.btn-back{width:100%;padding:12px;background:white;color:var(--gray-600);font-weight:500;font-size:.9rem;border:1px solid var(--gray-200);border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .15s;margin-top:10px}
.btn-back:hover{background:var(--gray-50)}
.err-box{display:none;background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;color:#991B1B}
@media(max-width:600px){
    .agent-grid,.paket-grid{grid-template-columns:1fr}
    .pay-grid{grid-template-columns:1fr}
    .form-row{grid-template-columns:1fr}
    .onboard-card{padding:24px 16px}
}
</style>
@endpush

@section('content')
    {{-- NAVBAR --}}
    @include('partials.navbar', ['navType' => 'onboarding'])

    <div class="onboard-wrap">
        {{-- ===== PROGRESS BAR ===== --}}
        <div class="progress-bar">
            <div class="progress-steps" id="progress-steps">
                @php $steps = ['Data diri','Pilih agent','Pilih paket','Pembayaran','Konsultasi']; @endphp
                @foreach($steps as $i => $label)
                    @php $n = $i + 1; @endphp
                    <div class="prog-step">
                        <div class="prog-circle {{ $n === 1 ? 'active' : '' }}" id="pc{{ $n }}">{{ $n }}</div>
                        <div class="prog-label {{ $n === 1 ? 'active' : '' }}" id="pl{{ $n }}">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="onboard-body">

            {{-- ===== STEP 1: DATA DIRI ===== --}}
            <div id="step1" class="onboard-card fade-in">
                <h2>Mulai Konsultasi Hukum</h2>
                <p>Isi data diri Anda untuk memulai sesi konsultasi</p>
                <div id="err1" class="err-box"></div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama lengkap *</label>
                        <input type="text" id="s1-nama" placeholder="Nama lengkap Anda"/>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP *</label>
                        <input type="tel" id="s1-hp" placeholder="08xxxxxxxxxx"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat email *</label>
                    <input type="email" id="s1-email" placeholder="email@contoh.com"/>
                </div>
                <div class="form-group">
                    <label>Bidang hukum yang dibutuhkan</label>
                    <select id="s1-bidang">
                        @foreach($bidang_hukum as $b)
                            <option>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="s1-agree"/>
                    <label for="s1-agree">Saya menyetujui <a href="#">syarat & ketentuan</a> layanan konsultasi CASP Indonesia</label>
                </div>
                <button class="btn-submit" onclick="nextStep(1)">Lanjutkan →</button>
            </div>

            {{-- ===== STEP 2: PILIH AGENT ===== --}}
            <div id="step2" class="onboard-card fade-in" style="display:none">
                <h2>Pilih Konsultan Hukum</h2>
                <p>Semua konsultan telah tersertifikasi dan berpengalaman di bidangnya</p>
                <div class="agent-grid">
                    @php
                    $warna = ['blue'=>'av-blue','indigo'=>'av-indigo','green'=>'av-green','orange'=>'av-orange','purple'=>'av-purple','red'=>'av-red'];
                    @endphp
                    @foreach($konsultan as $k)
                    <div class="agent-card {{ $loop->iteration === 2 ? 'selected' : '' }}"
                         id="agent-{{ $k->id }}"
                         onclick="selectAgent({{ $k->id }}, '{{ addslashes($k->nama_lengkap) }}')">
                        <div class="agent-avatar {{ $warna[$k->warna_avatar] ?? 'av-blue' }}">{{ $k->inisial }}</div>
                        @if($k->status === 'online')
                            <div class="agent-online">Online</div>
                        @else
                            <div class="agent-sibuk">Sibuk</div>
                        @endif
                        <div class="agent-name">{{ $k->nama_lengkap }}</div>
                        <div class="agent-spec">{{ $k->spesialisasi }}</div>
                        <div class="agent-exp">{{ $k->pengalaman_tahun }} tahun pengalaman</div>
                    </div>
                    @endforeach
                </div>
                <button class="btn-submit" onclick="nextStep(2)">Lanjutkan →</button>
                <button class="btn-back" onclick="prevStep(2)">← Kembali</button>
            </div>

            {{-- ===== STEP 3: PILIH PAKET ===== --}}
            <div id="step3" class="onboard-card fade-in" style="display:none">
                <h2>Pilih Paket Konsultasi</h2>
                <p>Pilih durasi yang sesuai dengan kebutuhan Anda</p>
                <div class="paket-grid">
                    @foreach($paket as $pid => $p)
                    <div class="paket-card {{ $pid === 2 ? 'selected' : '' }}"
                         id="paket-{{ $pid }}"
                         onclick="selectPaket({{ $pid }}, {{ $p['harga'] }}, '{{ $p['label'] }}')">
                        @isset($p['populer'])
                            <div class="paket-badge">Paling populer</div>
                        @endisset
                        <div class="paket-dur">{{ $p['label'] }}</div>
                        <div class="paket-price">Rp {{ number_format($p['harga'], 0, ',', '.') }}</div>
                        <ul class="paket-feat">
                            @foreach($p['fitur'] as $f)
                                <li>{{ $f }}</li>
                            @endforeach
                            @foreach($p['fitur_off'] as $f)
                                <li class="disabled">{{ $f }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
                <button class="btn-submit" onclick="nextStep(3)">Lanjutkan →</button>
                <button class="btn-back" onclick="prevStep(3)">← Kembali</button>
            </div>

            {{-- ===== STEP 4: PEMBAYARAN ===== --}}
            <div id="step4" class="onboard-card fade-in" style="display:none">
                <h2>Pembayaran</h2>
                <p>Selesaikan pembayaran untuk memulai sesi konsultasi</p>
                <div class="pay-grid">
                    <div>
                        <p style="font-size:.82rem;font-weight:600;color:var(--gray-600);margin-bottom:10px;letter-spacing:.02em">METODE PEMBAYARAN</p>
                        <div class="pay-methods">
                            @foreach(['qris'=>['QRIS','Semua e-wallet & bank'],'bca'=>['Transfer BCA','Virtual account'],'gopay'=>['GoPay','Via aplikasi Gojek'],'ovo'=>['OVO','Via aplikasi OVO']] as $mid => $ml)
                            <div class="pay-method {{ $mid === 'bca' ? 'selected' : '' }}"
                                 id="pm-{{ $mid }}"
                                 onclick="selectPayment('{{ $mid }}')">
                                <div class="pay-method-radio"><div class="pay-dot"></div></div>
                                <div>
                                    <div class="pay-method-label">{{ $ml[0] }}</div>
                                    <div class="pay-method-sub">{{ $ml[1] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="qr-box" id="qr-section" style="display:none">
                            <div class="qr-placeholder">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="3" width="8" height="8" rx="1" stroke="#94A3B8" stroke-width="1.5"/>
                                    <rect x="13" y="3" width="8" height="8" rx="1" stroke="#94A3B8" stroke-width="1.5"/>
                                    <rect x="3" y="13" width="8" height="8" rx="1" stroke="#94A3B8" stroke-width="1.5"/>
                                    <rect x="13" y="13" width="8" height="8" rx="1" stroke="#94A3B8" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <p style="font-size:.72rem;color:var(--gray-400)">Scan untuk bayar</p>
                        </div>
                    </div>
                    <div>
                        <p style="font-size:.82rem;font-weight:600;color:var(--gray-600);margin-bottom:10px;letter-spacing:.02em">RINGKASAN PESANAN</p>
                        <div class="pay-summary">
                            <div class="pay-row"><span>Konsultan</span><span id="pay-consultant">–</span></div>
                            <div class="pay-row"><span>Paket</span><span id="pay-paket">–</span></div>
                            <div class="pay-row"><span>Harga paket</span><span id="pay-harga">–</span></div>
                            <div class="pay-row"><span>Biaya layanan</span><span>Rp 5.000</span></div>
                            <div class="pay-row total"><span>Total</span><span id="pay-total">–</span></div>
                            <div class="pay-timer">Selesaikan dalam: <span id="pay-countdown">14:59</span></div>
                        </div>
                        <button class="btn-submit" style="margin-top:16px" id="pay-btn" onclick="confirmPayment()">Konfirmasi Pembayaran</button>
                        <p style="text-align:center;font-size:.72rem;color:var(--gray-400);margin-top:10px">🔒 Pembayaran aman & terenkripsi</p>
                    </div>
                </div>
                <button class="btn-back" onclick="prevStep(4)">← Kembali</button>
            </div>

        </div>{{-- /onboard-body --}}
    </div>{{-- /onboard-wrap --}}
@endsection

@push('scripts')
<script>
// =============== STATE ===============
let currentStep   = 1;
let selectedAgent = {{ $konsultan->count() >= 2 ? $konsultan->values()->get(1)->id : ($konsultan->first()->id ?? 1) }};
let selectedAgentNama = '{{ addslashes($konsultan->values()->get(1)?->nama_lengkap ?? '') }}';
let selectedPaket = 2;
let selectedPaketHarga = 90000;
let selectedPaketLabel = '2 jam';
let selectedMetode = 'bca';
let payTimerSec   = 899;
let payInterval;

const rupiah = (v) => 'Rp ' + v.toLocaleString('id-ID');
const fmtTime = (s) => { let m=Math.floor(s/60),sec=s%60; return String(m).padStart(2,'0')+':'+String(sec).padStart(2,'0'); };
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// =============== PROGRESS ===============
function setStep(s) {
    for (let i = 1; i <= 5; i++) {
        const el = document.getElementById('step' + i);
        if (el) el.style.display = (i === s) ? 'block' : 'none';
        document.getElementById('pc' + i).className = 'prog-circle' + (i < s ? ' done' : i === s ? ' active' : '');
        document.getElementById('pl' + i).className = 'prog-label'  + (i === s ? ' active' : '');
    }
    currentStep = s;
    window.scrollTo(0, 0);
    if (s === 4) { updatePaySummary(); startPayTimer(); }
}

function showErr(id, msg) {
    const box = document.getElementById(id);
    if (!box) return;
    box.textContent = msg;
    box.style.display = 'block';
}
function hideErr(id) {
    const box = document.getElementById(id);
    if (box) box.style.display = 'none';
}

// =============== STEP NAVIGATION ===============
async function nextStep(s) {
    hideErr('err' + s);

    if (s === 1) {
        const nama   = document.getElementById('s1-nama').value.trim();
        const hp     = document.getElementById('s1-hp').value.trim();
        const email  = document.getElementById('s1-email').value.trim();
        const bidang = document.getElementById('s1-bidang').value;
        const agree  = document.getElementById('s1-agree').checked;

        if (!nama || !hp || !email) { showErr('err1', 'Harap isi semua field yang wajib (*).'); return; }
        if (!agree) { showErr('err1', 'Harap setujui syarat & ketentuan terlebih dahulu.'); return; }

        try {
            const res  = await fetch('{{ route('onboarding.step1') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ nama, hp, email, bidang }),
            });
            const data = await res.json();
            if (!data.success) { showErr('err1', JSON.stringify(data.errors || data.message)); return; }
        } catch { showErr('err1', 'Terjadi kesalahan. Coba lagi.'); return; }
    }

    if (s === 2) {
        await fetch('{{ route('onboarding.step2') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ konsultan_id: selectedAgent }),
        });
    }

    if (s === 3) {
        await fetch('{{ route('onboarding.step3') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ paket: selectedPaket }),
        });
    }

    setStep(s + 1);
}

function prevStep(s) { setStep(s - 1); }

// =============== AGENT ===============
function selectAgent(id, nama) {
    document.querySelectorAll('.agent-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('agent-' + id).classList.add('selected');
    selectedAgent     = id;
    selectedAgentNama = nama;
}

// =============== PAKET ===============
function selectPaket(id, harga, label) {
    document.querySelectorAll('.paket-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('paket-' + id).classList.add('selected');
    selectedPaket      = id;
    selectedPaketHarga = harga;
    selectedPaketLabel = label;
    updatePaySummary();
}

// =============== PAYMENT ===============
function selectPayment(type) {
    selectedMetode = type;
    ['qris','bca','gopay','ovo'].forEach(t => {
        const el = document.getElementById('pm-' + t);
        if (!el) return;
        el.classList.toggle('selected', t === type);
    });
    document.getElementById('qr-section').style.display = (type === 'qris') ? 'block' : 'none';
}

function updatePaySummary() {
    const el = (id) => document.getElementById(id);
    if (el('pay-consultant')) el('pay-consultant').textContent = selectedAgentNama;
    if (el('pay-paket'))      el('pay-paket').textContent      = selectedPaketLabel;
    if (el('pay-harga'))      el('pay-harga').textContent      = rupiah(selectedPaketHarga);
    if (el('pay-total'))      el('pay-total').textContent      = rupiah(selectedPaketHarga + 5000);
}

function startPayTimer() {
    clearInterval(payInterval);
    payTimerSec = 899;
    payInterval = setInterval(() => {
        if (payTimerSec <= 0) { clearInterval(payInterval); return; }
        payTimerSec--;
        const el = document.getElementById('pay-countdown');
        if (el) el.textContent = fmtTime(payTimerSec);
    }, 1000);
}

async function confirmPayment() {
    const btn = document.getElementById('pay-btn');
    btn.textContent = 'Memproses...';
    btn.disabled = true;
    clearInterval(payInterval);

    try {
        const res  = await fetch('{{ route('pembayaran.konfirmasi') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({
                metode:       selectedMetode,
                konsultan_id: selectedAgent,
                paket:        selectedPaket,
            }),
        });
        const data = await res.json();

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Terjadi kesalahan saat pembayaran.');
            btn.textContent = 'Konfirmasi Pembayaran';
            btn.disabled = false;
        }
    } catch {
        alert('Gagal memproses pembayaran. Silakan coba lagi.');
        btn.textContent = 'Konfirmasi Pembayaran';
        btn.disabled = false;
    }
}

// Init
updatePaySummary();
</script>
@endpush