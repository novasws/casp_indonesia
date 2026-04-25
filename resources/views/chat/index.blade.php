@extends('layouts.app')

@section('title', 'Konsultasi – ' . $konsultasi->konsultan->nama_lengkap . ' – CASP Indonesia')

@section('content')

@if($konsultasi->status === 'menunggu')
<div class="flex flex-col h-[100dvh] overflow-hidden bg-slate-50 relative">
    {{-- Waiting Room UI --}}
    <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-6 z-50">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl border border-slate-100 transform transition-all">
            <div class="w-20 h-20 bg-brand-100 text-brand-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner ring-4 ring-white animate-pulse">
                <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
            <h3 class="text-2xl font-serif text-brand-900 mb-2">Harap Tunggu...</h3>
            <div class="inline-flex items-center justify-center bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full text-[11px] mb-4 border border-slate-200">
                Paket: {{ $konsultasi->paket }} Jam
            </div>
            <p class="text-slate-500 mb-6 text-sm leading-relaxed">Konsultan saat ini sedang melayani klien lain. Sesi Anda sedang diantrekan ke dalam sistem kami.</p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Antrean Anda</div>
                    <div class="text-3xl font-bold text-brand-600 font-mono">{{ $antreanKe ?? 1 }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estimasi Waktu Tunggu</div>
                    <div id="wait-timer" class="text-xl font-bold text-amber-500 font-mono tabular-nums leading-tight mt-1 mb-1">
                        {{ gmdate('H:i:s', $estimasiTungguDetik) }}
                    </div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest border-t border-slate-200 pt-1 mt-1">
                        Mulai: <span class="text-brand-600">Jam {{ $estimasiJamMulai }}</span>
                    </div>
                </div>
            </div>

            <p class="text-xs text-slate-400 italic mb-4">Halaman ini akan memuat otomatis ketika giliran Anda tiba. Mohon jangan ditutup.</p>
            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left">
                <div class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    KODE SESI ANDA
                </div>
                <div class="flex items-center justify-between bg-white border border-amber-100 rounded-lg p-2.5 mt-2 mb-2">
                    <div class="text-xl font-bold text-slate-800 font-mono tracking-widest" id="token-code-1">{{ $konsultasi->token_sesi ?? 'N/A' }}</div>
                    <button onclick="copyToClipboard('{{ $konsultasi->token_sesi }}', this)" class="flex items-center gap-1 px-3 py-1.5 bg-brand-50 text-brand-600 hover:bg-brand-100 hover:text-brand-700 transition-colors rounded-md border border-brand-200 text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Salin
                    </button>
                </div>
                <p class="text-[11px] text-amber-700 leading-tight mt-1">SIMPAN kode ini. Jika Anda menutup browser, Anda dapat menggunakan kode ini untuk masuk kembali ke ruang antrean Anda melalui menu "Lacak Sesi" di halaman utama.</p>
            </div>
            
            <a href="{{ route('landing') }}" class="inline-flex items-center justify-center w-full mt-5 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors text-sm border border-slate-200">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const URL_FETCH = '{{ route('chat.fetch-pesan', $konsultasi->id) }}';
    let estimasiDetik = {{ $estimasiTungguDetik }};
    
    // Countdown Timer UI
    if (estimasiDetik > 0) {
        setInterval(() => {
            if (estimasiDetik > 0) {
                estimasiDetik--;
                let h = Math.floor(estimasiDetik / 3600);
                let m = Math.floor((estimasiDetik % 3600) / 60);
                let s = estimasiDetik % 60;
                document.getElementById('wait-timer').textContent = 
                    String(h).padStart(2, '0') + ':' + 
                    String(m).padStart(2, '0') + ':' + 
                    String(s).padStart(2, '0');
            }
        }, 1000);
    }

    function checkQueueStatus() {
        fetch(URL_FETCH)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'aktif') {
                    window.location.reload();
                }
            })
            .catch(e => console.error(e));
    }

    setInterval(checkQueueStatus, 3000);
</script>
@endpush

@elseif($konsultasi->status === 'terjadwal')
<div class="flex flex-col h-screen overflow-hidden bg-slate-50 relative">
    {{-- Terjadwal UI --}}
    <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-6 z-50">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl border border-slate-100 transform transition-all">
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner ring-4 ring-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-2xl font-serif text-brand-900 mb-2">Konsultasi Terjadwal</h3>
            <div class="inline-flex items-center justify-center bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full text-[11px] mb-4 border border-slate-200">
                Paket: {{ $konsultasi->paket }} Jam
            </div>
            <p class="text-slate-500 mb-4 text-sm leading-relaxed">Sesi konsultasi Anda dengan <strong>{{ $konsultasi->konsultan->nama }}</strong> dijadwalkan pada jam shift berikutnya.</p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Posisi Antrean</div>
                    <div class="text-lg font-bold text-brand-600 font-mono mt-1">Ke- {{ $antreanKe }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estimasi Mulai</div>
                    <div class="text-lg font-bold text-amber-500 font-mono tabular-nums leading-tight mt-1">
                        Jam {{ $estimasiJamMulai }}
                    </div>
                </div>
            </div>
            <p class="text-xs text-slate-400 italic mb-4">Halaman ini akan otomatis merefresh saat konsultan memulai sesi.</p>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left mt-2">
                <div class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    KODE SESI ANDA
                </div>
                <div class="flex items-center justify-between bg-white border border-amber-100 rounded-lg p-2.5 mt-2 mb-2">
                    <div class="text-xl font-bold text-slate-800 font-mono tracking-widest" id="token-code-2">{{ $konsultasi->token_sesi ?? 'N/A' }}</div>
                    <button onclick="copyToClipboard('{{ $konsultasi->token_sesi }}', this)" class="flex items-center gap-1 px-3 py-1.5 bg-brand-50 text-brand-600 hover:bg-brand-100 hover:text-brand-700 transition-colors rounded-md border border-brand-200 text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Salin
                    </button>
                </div>
                <p class="text-[11px] text-amber-700 leading-tight mt-1">SIMPAN kode ini. Jika Anda menutup browser, Anda dapat menggunakan kode ini untuk kembali ke ruang tunggu Anda melalui menu "Lacak Sesi" di halaman utama.</p>
            </div>

            <a href="{{ route('landing') }}" class="inline-flex items-center justify-center w-full mt-5 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors text-sm border border-slate-200">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Tinggalkan / Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // We don't need a running countdown for shift queues since the shift start time varies. We just poll for status.

    function checkJadwalStatus() {
        fetch('{{ route('chat.status', $konsultasi->id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'aktif') {
                    window.location.reload();
                }
            })
            .catch(e => console.error(e));
    }

    setInterval(checkJadwalStatus, 5000);
</script>
@endpush

@else
<div class="flex flex-col h-[100dvh] overflow-hidden bg-slate-50">
    
    {{-- Navbar Konsultasi Klien --}}
    <div class="bg-brand-900 px-4 py-3 md:px-6 md:py-4 flex items-center justify-between shrink-0 shadow-md relative z-20">
        <div class="flex items-center gap-3 md:gap-4 text-white">
            <div class="w-10 h-10 md:w-12 md:h-12 shrink-0 rounded-full bg-brand-800 border-2 border-brand-500 overflow-hidden flex items-center justify-center font-serif text-lg md:text-xl">
                @if($konsultasi->konsultan->foto)
                    <img src="{{ asset('storage/' . $konsultasi->konsultan->foto) }}" alt="{{ $konsultasi->konsultan->nama_lengkap }}" class="w-full h-full object-cover">
                @else
                    {{ $konsultasi->konsultan->inisial }}
                @endif
            </div>
            <div class="min-w-0">
                <h3 class="font-bold text-base md:text-lg leading-tight truncate">{{ $konsultasi->konsultan->nama }}</h3>
                <div class="text-[10px] md:text-xs text-brand-300 font-medium flex items-center gap-1.5 mt-0.5 truncate">
                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 shrink-0 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="truncate">Online &bull; {{ $konsultasi->konsultan->bidang_hukum }}</span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2 md:gap-4 shrink-0 pl-2">
            <div class="bg-white/10 border border-white/20 rounded-lg md:rounded-xl px-2.5 py-1 md:px-4 md:py-2 text-center shadow-inner">
                <div class="text-[9px] md:text-[10px] text-brand-300 font-bold uppercase tracking-wider mb-0.5">Sisa Waktu</div>
                <div id="chat-timer" class="text-sm md:text-xl font-bold text-gold-400 font-mono tracking-wider tabular-nums leading-none">
                    {{ gmdate('i:s', $sisaDetik) }}
                </div>
            </div>
            <a href="{{ route('landing') }}" class="w-8 h-8 md:w-10 md:h-10 shrink-0 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors border border-white/10" title="Keluar">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>
    </div>
    
    <div class="h-1 bg-slate-200 w-full shrink-0">
        <div id="chat-progress" class="h-full bg-gold-500 transition-all duration-1000 ease-linear shadow-[0_0_10px_rgba(234,179,8,0.5)]" style="width:{{ $durasi > 0 ? round($sisaDetik / $durasi * 100) : 0 }}%"></div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-slate-50 relative z-0">
        <div class="absolute inset-0 bg-brand-50/40 z-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-50"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto space-y-6 flex flex-col justify-end min-h-full" id="chat-messages">
            <div class="text-center py-4">
                <span class="px-4 py-1.5 bg-white border border-slate-200 text-slate-500 text-xs font-bold rounded-full shadow-sm">
                    Sesi aman terenkripsi end-to-end
                </span>
            </div>

            {{-- Tampilan ID Sesi untuk kemudahan klien menyimpan Token --}}
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl shadow-sm text-center">
                <p class="text-xs text-amber-700 font-medium mb-1">ID SESI KONSULTASI ANDA</p>
                <div class="flex items-center justify-center gap-3 bg-white border border-amber-100 rounded-lg p-2 max-w-sm mx-auto mt-2 mb-2">
                    <div class="text-xl font-bold text-slate-800 font-mono tracking-widest">{{ $konsultasi->token_sesi }}</div>
                    <button onclick="copyToClipboard('{{ $konsultasi->token_sesi }}', this)" class="flex items-center gap-1 px-3 py-1.5 bg-brand-50 text-brand-600 hover:bg-brand-100 hover:text-brand-700 transition-colors rounded-md border border-brand-200 text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Salin
                    </button>
                </div>
                <p class="text-[11px] text-amber-600 leading-relaxed max-w-lg mx-auto">
                    Simpan kode unik ini! Anda akan membutuhkannya untuk mengunduh riwayat/transkrip obrolan ini di kemudian hari melalui menu <strong>Lacak Sesi</strong> pada halaman utama.
                </p>
            </div>

            {{-- Welcome Message from Consultant --}}
            <div class="flex items-start gap-3 fade-in">
                <div class="w-10 h-10 shrink-0 rounded-full bg-brand-800 text-gold-400 flex items-center justify-center font-bold border-2 border-brand-700 shadow-sm text-sm">
                    {{ $konsultasi->konsultan->inisial }}
                </div>
                <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-sm max-w-[85%] sm:max-w-[70%] shadow-sm relative group text-left pt-3">
                    <h4 class="text-xs font-bold text-slate-800 mb-1 flex items-center justify-between">
                        {{ $konsultasi->konsultan->nama }} 
                        <span class="text-slate-400 font-normal ml-3">{{ $konsultasi->mulai_at?->format('H:i') ?? now()->format('H:i') }}</span>
                    </h4>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">Halo, selamat datang di sesi konsultasi CASP Indonesia. Saya {{ $konsultasi->konsultan->nama }}, pakar {{ strtolower($konsultasi->konsultan->bidang_hukum) }}. Silakan ceritakan kronologis detail permasalahan Anda secara langsung di bawah.</p>
                </div>
            </div>

            {{-- Fetched messages via script --}}
        </div>
        
        {{-- Sisa padding scroll --}}
        <div id="chat-bottom" class="h-4"></div>
    </div>

    {{-- Locked Screen Overlay --}}
    <div id="chat-locked" class="hidden absolute inset-0 z-50 bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-6">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl border border-slate-100 transform transition-all">
            <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner ring-4 ring-white">
                ⏱️
            </div>
            <h3 class="text-2xl font-serif text-brand-900 mb-2">Durasi Telah Habis</h3>
            <p class="text-slate-500 mb-8 text-sm leading-relaxed">Sesi konsultasi live Anda telah otomatis ditutup oleh sistem. Transkrip dari percakapan ini dapat Anda unduh untuk keperluan dokumentasi legal.</p>
            
            <div class="space-y-3">
                <a href="{{ route('chat.transkrip', $konsultasi->id) }}" class="flex items-center justify-center w-full py-3.5 bg-brand-900 hover:bg-black text-white font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Unduh Transkrip PDF/TXT
                </a>
                <a href="{{ route('onboarding.index') }}" class="flex items-center justify-center w-full py-3.5 bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 rounded-xl transition-colors">
                    Mulai Sesi Baru
                </a>
                <a href="{{ route('landing') }}" class="block text-center pt-2 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="bg-white p-4 border-t border-slate-200 shrink-0 relative z-20 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)]" id="input-container">
        <div class="max-w-4xl mx-auto relative flex items-end gap-3">
            <button type="button" class="w-12 h-12 shrink-0 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
            </button>
            
            <textarea id="chatInput" placeholder="Ketik kronologis atau pertanyaan Anda..." class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none transition-all placeholder:text-slate-400 max-h-32" rows="1"></textarea>
            
            <button onclick="sendMessage()" class="w-12 h-12 shrink-0 bg-brand-600 hover:bg-brand-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center group">
                <svg class="w-5 h-5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const KONS_ID     = {{ $konsultasi->id }};
    const SISA_DETIK  = {{ $sisaDetik }};
    const TOTAL_DETIK = {{ $durasi }};
    const URL_FETCH   = '{{ route('chat.fetch-pesan', $konsultasi->id) }}';
    const URL_KIRIM   = '{{ route('chat.kirim-pesan', $konsultasi->id) }}';
    const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

    let timerSec = SISA_DETIK;
    let renderedIds = [];
    let tInterval = null;
    let fetchInterval = null;

    // --- Timer System ---
    if (timerSec > 0) {
        tInterval = setInterval(() => {
            if (timerSec <= 0) {
                lockChat();
                return;
            }
            timerSec--;
            document.getElementById('chat-timer').textContent = fmtTime(timerSec);
            document.getElementById('chat-progress').style.width = (timerSec / TOTAL_DETIK * 100) + '%';
        }, 1000);
    } else {
        lockChat();
    }

    function lockChat() {
        if(tInterval) clearInterval(tInterval);
        if(fetchInterval) clearInterval(fetchInterval);
        document.getElementById('chat-locked').classList.remove('hidden');
        document.getElementById('input-container').style.display = 'none';
    }

    function fmtTime(s) {
        let m = Math.floor(s / 60), sec = s % 60;
        return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
    }

    // --- Auto Resize Textarea ---
    const tx = document.getElementById('chatInput');
    tx.setAttribute('style', 'height:' + (tx.scrollHeight) + 'px;overflow-y:hidden;');
    tx.addEventListener("input", function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    }, false);

    tx.addEventListener("keydown", function(e) {
        if(e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });

    // --- Retrieve Msgs AJAX Polling ---
    async function fetchMessages() {
        try {
            const res = await fetch(URL_FETCH);
            const data = await res.json();
            if(data.success) {
                if(data.status === 'selesai' || data.status === 'gagal') {
                    lockChat();
                }
                let isNew = false;
                data.pesans.forEach(p => {
                    if(!renderedIds.includes(p.id)) {
                        appendMessage(p);
                        renderedIds.push(p.id);
                        isNew = true;
                    }
                });
                if(isNew) {
                    document.getElementById('chat-bottom').scrollIntoView({behavior: "smooth"});
                }
            }
        } catch(e) {}
    }

    // Initial fetch, then poll every 3 seconds
    fetchMessages();
    fetchInterval = setInterval(fetchMessages, 3000);

    // --- Send Message ---
    async function sendMessage() {
        const text = tx.value.trim();
        if(!text) return;

        // Optimistic UI append
        const tempId = 'temp-' + Date.now();
        appendMessage({id: tempId, pengirim: 'klien', isi: text, waktu: '...'});
        renderedIds.push(tempId); // so we don't double render this exact one
        
        tx.value = '';
        tx.style.height = '48px';
        document.getElementById('chat-bottom').scrollIntoView({behavior: "smooth"});

        // HTTP POST
        try {
            const res = await fetch(URL_KIRIM, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ isi: text })
            });
            const data = await res.json();
            if(data.success) {
                renderedIds.push(data.pesan.id); // Hindari rendered double dari polling
                fetchMessages(); // re-sync
            }
        } catch(e) {}
    }

    // --- DOM Append ---
    const container = document.getElementById('chat-messages');

    function appendMessage(p) {
        const div = document.createElement('div');
        div.className = "flex items-start gap-3 fade-in mt-4";
        
        if(p.pengirim === 'klien') {
            div.className += " justify-end";
            div.innerHTML = `
                <div class="bg-brand-600 p-4 rounded-2xl rounded-tr-sm max-w-[85%] sm:max-w-[70%] shadow-[0_4px_15px_-5px_rgba(37,99,235,0.3)] relative group text-left">
                    <p class="text-white text-sm leading-relaxed whitespace-pre-wrap">${escapeHTML(p.isi)}</p>
                    <div class="text-brand-200 text-[10px] text-right mt-1.5 flex items-center justify-end gap-1">
                        ${p.waktu} <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
            `;
        } else {
            div.innerHTML = `
                <div class="w-10 h-10 shrink-0 rounded-full bg-brand-800 text-gold-400 flex items-center justify-center font-bold border-2 border-brand-700 shadow-sm text-sm">
                    KA
                </div>
                <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-sm max-w-[85%] sm:max-w-[70%] shadow-sm relative group text-left">
                    <h4 class="text-xs font-bold text-slate-800 mb-1 flex items-center justify-between">
                        Konsultan
                        <span class="text-slate-400 font-normal ml-3">${p.waktu}</span>
                    </h4>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">${escapeHTML(p.isi)}</p>
                </div>
            `;
        }
        
        container.appendChild(div);
    }
    
    function escapeHTML(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
</script>
@endpush
@endif

@push('scripts')
<script>
    function copyToClipboard(text, btnElement) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Tersalin!`;
            btnElement.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
            btnElement.classList.remove('bg-brand-50', 'text-brand-600', 'border-brand-200');
            setTimeout(() => {
                btnElement.innerHTML = originalHTML;
                btnElement.classList.remove('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
                btnElement.classList.add('bg-brand-50', 'text-brand-600', 'border-brand-200');
            }, 2000);
        });
    }
</script>
@endpush
@endsection