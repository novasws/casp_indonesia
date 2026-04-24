@extends('layouts.app')

@section('title', 'CASP Indonesia – Pakar Konsultasi Hukum')

@push('styles')
<style>
    /* Gradient Animation for Hero Background */
    .bg-animated-gradient {
        background: linear-gradient(-45deg, #0A2342, #1A4A8A, #123364, #06162E);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    /* Elegant Modal Backdrop Filter */
    .modal-overlay {
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
</style>
@endpush

@section('content')
    {{-- NAVBAR --}}
    @include('partials.navbar', ['navType' => 'landing'])

    {{-- ===== HERO ===== --}}
    <section class="relative bg-animated-gradient text-white overflow-hidden min-h-[75vh] flex items-center pt-10 pb-16">
        {{-- Decorative Circles --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute w-[800px] h-[800px] bg-brand-400/20 rounded-full blur-[100px] -top-[200px] -left-[200px] animate-float opacity-50"></div>
            <div class="absolute w-[600px] h-[600px] bg-brand-300/10 rounded-full blur-[80px] bottom-[10%] -right-[150px] animate-pulse-slow"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 py-10 mt-6 lg:mt-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center animate-fade-in-up">
                {{-- Text Content Kiri --}}
                <div class="text-left">
                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-4 py-2 rounded-full text-sm font-medium mb-8 backdrop-blur-md">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        {{ $hero['badge'] ?? 'Konsultan hukum bersertifikat · Online 24/7' }}
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-serif leading-tight mb-6">
                        @php
                            $judul = $hero['judul'] ?? 'Hallo CASP';
                            $parts = explode(' ', $judul, 2);
                        @endphp
                        {{ $parts[0] ?? '' }} <span class="bg-gradient-to-r from-gold-300 to-gold-500 bg-clip-text text-transparent italic">{{ $parts[1] ?? '' }}</span>
                    </h1>
                    <p class="text-lg md:text-xl text-brand-100 max-w-xl mb-10 font-light leading-relaxed">
                        {{ $hero['deskripsi'] ?? 'Pusat konsultasi kebijakan ruang angkasa, udara, dan layanan hukum terpadu. Terjamin aman, privat, dan profesional bersama pakar berpengalaman di bidangnya.' }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-start justify-start gap-4">
                        <a href="{{ route('onboarding.index') }}" class="group relative inline-flex items-center justify-center px-8 py-4 font-semibold text-brand-900 bg-white rounded-xl overflow-hidden transition-all hover:scale-105 hover:shadow-[0_0_40px_rgba(255,255,255,0.3)] w-full sm:w-auto">
                            Mulai Konsultasi
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="#konsultan-section" class="inline-flex items-center justify-center px-8 py-4 font-medium text-white bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition-all backdrop-blur-sm w-full sm:w-auto">
                            Pelajari lebih lanjut
                        </a>
                    </div>
                </div>
                
                {{-- Gambar / Logo Kanan --}}
                <div class="hidden lg:flex justify-center relative items-center">
                    <div class="absolute inset-0 bg-brand-400/20 blur-3xl rounded-full scale-110"></div>
                    <img src="{{ asset('images/logo.png') }}" alt="CASP Indonesia" class="relative z-10 w-[420px] h-[420px] rounded-full object-cover animate-float shadow-2xl border-[6px] border-white/10"/>
                </div>
            </div>

            {{-- Stats Glassmorphism Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto mt-24 fade-on-scroll">
                @foreach($stats as $key => $val)
                    <div class="glass p-6 rounded-2xl text-center border-t border-l border-white/20 hover:-translate-y-1 transition-transform duration-300">
                        <div class="font-serif text-3xl md:text-4xl text-white mb-1 drop-shadow-sm">{{ $val }}</div>
                        <div class="text-sm font-medium text-brand-200">
                            @if($key === 'kasus_selesai') Kasus Diselesaikan
                            @elseif($key === 'kepuasan') Tingkat Kepuasan
                            @elseif($key === 'konsultan') Konsultan Aktif
                            @else Tarif Mulai Dari
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== KONSULTAN (DAFTAR PEMBIMBING) ===== --}}
    <section class="py-24 bg-white" id="konsultan-section">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="text-center mb-16 fade-on-scroll">
                <span class="text-brand-500 font-bold uppercase tracking-wider text-sm">Daftar Pembimbing</span>
                <h2 class="text-4xl md:text-5xl font-serif text-brand-900 mt-3 mb-4">Pakar Hukum Terbaik</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Semua konsultan kami telah tersertifikasi resmi dan berpengalaman menangani berbagai kompleksitas kasus di bidangnya.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($konsultan as $index => $k)
                    @php
                        $colors = ['bg-blue-50 text-blue-700', 'bg-emerald-50 text-emerald-700', 'bg-amber-50 text-amber-700', 'bg-purple-50 text-purple-700', 'bg-rose-50 text-rose-700'];
                        $colorClass = $colors[$index % count($colors)];
                        
                        $words = array_values(array_filter(array_map('trim', explode(' ', str_replace(['Dr.', 'S.H.', 'M.H.', 'M.Kn', ','], '', $k->nama)))));
                        $initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));
                        
                        $imgName = 'admin' . ($index + 1) . '.jpg';
                        $hasLocalImg = file_exists(public_path('images/' . $imgName));
                        if ($k->foto) {
                            $imgUrl = asset('storage/' . $k->foto);
                            $hasImg = true;
                        } else {
                            $imgUrl = $hasLocalImg ? asset('images/' . $imgName) : '';
                            $hasImg = $hasLocalImg;
                        }
                    @endphp
                    <div class="group relative bg-white rounded-2xl p-8 border border-slate-200 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:border-brand-300 hover:shadow-[0_20px_40px_-15px_rgba(37,99,235,0.15)] fade-on-scroll konsultan-card"
                         data-nama="{{ $k->nama }}"
                         data-inisial="{{ $initials }}"
                         data-img="{{ $imgUrl }}"
                         data-bg="{{ explode(' ', $colorClass)[0] }}"
                         data-text="{{ explode(' ', $colorClass)[1] }}"
                         data-bidang="{{ $k->bidang_hukum }}"
                         data-pengalaman="{{ $k->pengalaman_tahun }}"
                         data-status="{{ $k->status == 'online' ? 'Online' : ($k->status == 'sibuk' ? 'Sibuk' : 'Offline') }}"
                         data-bio="{{ e($k->bio ?? '') }}"
                         data-quote="{{ e($k->quote ?? '') }}">

                        <div class="flex flex-col items-center text-center">
                            @if($hasImg)
                                <img src="{{ $imgUrl }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($k->nama) }}&background={{ str_replace('#', '', $k->warna_avatar ?? '1E5EBF') }}&color=fff';" alt="{{ $k->nama }}" class="w-24 h-24 rounded-full object-cover mb-4 shadow-md group-hover:scale-110 transition-transform duration-300 border-4 border-slate-50">
                            @else
                                <div class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-bold mb-4 {{ $colorClass }} group-hover:scale-110 transition-transform duration-300 shadow-md border-4 border-slate-50">
                                    {{ $initials }}
                                </div>
                            @endif
                            
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full mb-4 {{ $k->status == 'online' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} text-xs font-semibold">
                                <span class="relative flex h-2 w-2">
                                  <span class="{{ $k->status == 'online' ? 'animate-ping' : '' }} absolute inline-flex h-full w-full rounded-full {{ $k->status == 'online' ? 'bg-emerald-400 opacity-75' : 'bg-amber-400 opacity-50' }}"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 {{ $k->status == 'online' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                </span>
                                {{ $k->status == 'online' ? 'Tersedia' : ($k->status == 'sibuk' ? 'Sedang Praktik' : 'Offline') }}
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $k->nama }}</h3>
                            <p class="text-slate-500 font-medium mb-3">{{ $k->bidang_hukum }}</p>
                            
                            <div class="w-full h-px bg-slate-100 my-4"></div>
                            
                            <div class="flex items-center justify-between w-full text-sm">
                                <span class="text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $k->pengalaman_tahun }} Tahun Exp.
                                </span>
                                <span class="text-brand-600 font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">Lihat <span aria-hidden="true">&rarr;</span></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== LAYANAN ===== --}}
    <section class="py-24 bg-slate-50 border-t border-slate-200" id="layanan-section">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 fade-on-scroll">
                <div class="max-w-2xl">
                    <span class="text-brand-500 font-bold uppercase tracking-wider text-sm">Ruang Lingkup</span>
                    <h2 class="text-4xl md:text-5xl font-serif text-brand-900 mt-3 mb-4">Pilih Bidang Layanan</h2>
                    <p class="text-slate-600 text-lg">Solusi hukum spesifik yang disesuaikan dengan kebutuhan personal maupun perusahaan Anda.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="#layanan-section" class="inline-flex items-center gap-2 text-brand-600 font-semibold hover:text-brand-800 transition-colors">Lihat Semua Kategori &darr;</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($layanan as $item)
                <a href="{{ route('layanan.detail', \Illuminate\Support\Str::slug($item['judul'])) }}" class="group bg-white p-8 rounded-2xl border border-slate-200 hover:border-brand-300 hover:shadow-xl transition-all duration-300 fade-on-scroll block">
                    <div class="w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-brand-100 transition-transform">
                        {{ $item['icon'] }}
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-brand-600 transition-colors">{{ $item['judul'] }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ $item['deskripsi'] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== CARA KERJA TIMELINE ===== --}}
    <section class="py-24 bg-brand-900 text-white relative overflow-hidden">
        {{-- Background Element --}}
        <div class="absolute right-0 top-0 w-1/3 h-full bg-brand-800 skew-x-12 opacity-50 translate-x-1/4"></div>
        
        <div class="container mx-auto px-6 max-w-5xl relative z-10">
            <div class="text-center mb-16 fade-on-scroll">
                <span class="text-brand-300 font-bold uppercase tracking-wider text-sm">Sistem Kami</span>
                <h2 class="text-4xl md:text-5xl font-serif text-white mt-3 mb-4">Alur Konsultasi Praktis</h2>
                <p class="text-brand-200 text-lg max-w-2xl mx-auto">Kami mendesain sistem yang sangat efisien. Dapatkan solusi dari ahlinya hanya dalam genggaman Anda.</p>
            </div>

            <div class="relative">
                {{-- Connector Line --}}
                <div class="hidden lg:block absolute top-[45px] left-0 right-0 h-0.5 bg-gradient-to-r from-brand-700 via-brand-500 to-brand-700"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-4 relative">
                    @foreach($cara_kerja as $index => $step)
                    <div class="flex flex-col items-center text-center fade-on-scroll delay-{{ ($index+1)*100 }} relative z-10">
                        <div class="w-24 h-24 rounded-full bg-brand-900 border-[6px] border-brand-800 flex items-center justify-center mb-6 shadow-[0_0_20px_rgba(0,0,0,0.5)] group-hover:border-brand-500 transition-colors">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white font-bold text-2xl">
                                {{ $step['num'] }}
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">{{ $step['judul'] }}</h4>
                        <p class="text-sm text-brand-200 px-2">{{ $step['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-20 text-center fade-on-scroll">
                <a href="{{ route('onboarding.index') }}" class="inline-flex items-center justify-center px-10 py-4 font-bold text-white bg-gradient-to-r from-gold-500 to-gold-600 rounded-xl hover:from-gold-400 hover:to-gold-500 shadow-[0_10px_25px_rgba(234,179,8,0.3)] transition-all hover:scale-105 border border-gold-400/50">
                    Mulai Sekarang &rarr;
                </a>
            </div>
        </div>
    </section>


    {{-- MODAL BIODATA PROFESIONAL --}}
    <div class="modal-overlay fixed inset-0 z-[100] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center px-4 py-6" id="biodataModal" onclick="closeModal(event)">
        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl transform scale-95 transition-transform duration-300 relative overflow-hidden" onclick="event.stopPropagation()">

            {{-- Header gradient bar --}}
            <div class="h-2 bg-gradient-to-r from-brand-700 via-brand-500 to-gold-500"></div>

            <div class="p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scrollbar">
                <button class="absolute top-6 right-6 w-9 h-9 bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-900 rounded-full flex items-center justify-center transition-colors focus:outline-none z-10" onclick="closeModal()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="flex flex-col md:flex-row gap-8 lg:gap-12">

                    {{-- LEFT: Foto + Stats --}}
                    <div class="flex flex-col items-center md:items-start md:w-64 shrink-0">
                        <div id="modalAbjad" class="w-32 h-32 rounded-2xl flex items-center justify-center text-4xl font-bold shadow-lg border-4 border-slate-50 overflow-hidden bg-cover bg-center mb-6"></div>

                        <h3 id="modalNama" class="text-3xl font-serif text-brand-900 mb-2 text-center md:text-left leading-tight"></h3>
                        <p id="modalBidang" class="text-brand-500 font-semibold text-base mb-6 text-center md:text-left"></p>

                        <div class="flex flex-row md:flex-col gap-3 w-full">
                            <div class="flex-1 bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                                <div id="modalExp" class="text-xl font-bold text-slate-800"></div>
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Pengalaman</div>
                            </div>
                            <div class="flex-1 bg-emerald-50 p-4 rounded-xl border border-emerald-100 text-center">
                                <div id="modalStatus" class="text-xl font-bold"></div>
                                <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mt-1">Status</div>
                            </div>
                            <div class="flex-1 bg-amber-50 p-4 rounded-xl border border-amber-100 text-center">
                                <div class="text-xl font-bold text-amber-600 flex justify-center items-center gap-1">
                                    5.0 <svg class="w-4 h-4 fill-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <div class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mt-1">Rating</div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Bio + Quote + CTA --}}
                    <div class="flex-1 flex flex-col min-w-0">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Tentang Konsultan</h4>
                        <div id="modalBio" class="text-slate-600 text-base leading-relaxed mb-8 flex-1 pr-2"></div>

                        <div class="bg-brand-50 border-l-4 border-brand-400 rounded-r-xl px-5 py-4 mb-7">
                            <p class="text-[10px] font-bold text-brand-400 uppercase tracking-widest mb-2">Kutipan Profesional</p>
                            <p id="modalQuote" class="text-slate-700 leading-relaxed text-sm italic"></p>
                        </div>

                        <a href="{{ route('onboarding.index') }}" class="block w-full py-4 bg-brand-900 hover:bg-brand-800 text-white font-bold rounded-xl transition-all hover:scale-[1.02] text-center shadow-md">
                            Pesan Jadwal Konsultasi &rarr;
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    @include('partials.footer')
@endsection

@push('scripts')
<script>
    // Wire up click events to all consultant cards using data-* attributes
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.konsultan-card').forEach(function (card) {
            card.addEventListener('click', function () {
                openModal(this.dataset);
            });
        });
    });

    function openModal(data) {
        const nama      = data.nama       || '';
        const inisial   = data.inisial    || '';
        const imgUrl    = data.img        || '';
        const bgClass   = data.bg         || 'bg-blue-50';
        const textClass = data.text       || 'text-blue-700';
        const bidang    = data.bidang     || '';
        const pengalaman = data.pengalaman || '';
        const status    = data.status     || '';
        let   bio       = data.bio        || '';
        let   quote     = data.quote      || '';

        if (!bio)   bio   = 'Informasi profil belum dilengkapi oleh konsultan.';
        if (!quote) quote = 'Pusat konsultasi kebijakan ruang angkasa, udara, dan layanan hukum terpadu.';

        document.getElementById('modalNama').innerText   = nama;
        document.getElementById('modalBidang').innerText = bidang;
        document.getElementById('modalBio').innerHTML    = bio.replace(/\n/g, '<br>');
        document.getElementById('modalQuote').innerHTML  = `"${quote.replace(/\n/g, '<br>')}"`;
        document.getElementById('modalExp').innerText    = pengalaman + ' Thn';

        // Avatar: photo or initials
        const abjad = document.getElementById('modalAbjad');
        if (imgUrl) {
            abjad.className   = 'w-28 h-28 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md ring-4 ring-white border border-slate-100 overflow-hidden bg-cover bg-center';
            abjad.style.backgroundImage = `url('${imgUrl}')`;
            abjad.innerText = '';
        } else {
            abjad.className   = `w-28 h-28 rounded-full flex items-center justify-center text-4xl font-bold mx-auto mb-6 shadow-md ring-4 ring-white border border-slate-100 ${bgClass} ${textClass}`;
            abjad.style.backgroundImage = '';
            abjad.innerText   = inisial;
        }

        // Status badge
        const st = document.getElementById('modalStatus');
        st.innerText  = status;
        st.className  = status === 'Online'
            ? 'text-xl font-bold mb-1 text-emerald-600'
            : 'text-xl font-bold mb-1 text-amber-600';

        // Show modal
        const modal = document.getElementById('biodataModal');
        const modalContent = modal.querySelector('div');
        modal.classList.remove('hidden');
        void modal.offsetWidth; // force reflow
        modal.classList.add('opacity-100');
        modal.classList.remove('opacity-0');
        modalContent.classList.add('scale-100');
        modalContent.classList.remove('scale-95');
    }

    function closeModal(e) {
        const modal = document.getElementById('biodataModal');
        const modalContent = modal.querySelector('div.bg-white');

        if (e && e.target !== modal && e.target.tagName !== 'BUTTON' && !e.target.closest('button')) return;

        modal.classList.add('opacity-0');
        modal.classList.remove('opacity-100');
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');

        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>
@endpush