@extends('layouts.app')

@section('title', 'Layanan ' . $layanan['judul'] . ' – CASP Indonesia')

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
</style>
@endpush

@section('content')
    @include('partials.navbar', ['navType' => 'landing'])

    {{-- HERO SECTION --}}
    <section class="relative bg-animated-gradient text-white overflow-hidden min-h-[40vh] md:min-h-[50vh] flex items-center pt-20 md:pt-24 pb-12 md:pb-16">
        <div class="container mx-auto px-6 relative z-10 text-center mt-6 md:mt-8">
            <a href="{{ route('landing') }}#layanan-section" class="inline-flex items-center gap-2 text-brand-300 font-medium hover:text-white transition-colors mb-6 md:mb-8 text-sm md:text-base">
                &larr; Kembali ke Daftar Layanan
            </a>
            
            <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center text-3xl md:text-4xl mx-auto mb-4 md:mb-6 backdrop-blur-md shadow-lg">
                {{ $layanan['icon'] }}
            </div>
            
            <h1 class="text-3xl md:text-6xl font-serif leading-tight mb-4 md:mb-6">
                {{ $layanan['judul'] }}
            </h1>
            
            <p class="text-base md:text-xl text-brand-100 max-w-2xl mx-auto font-light leading-relaxed mb-6 flex flex-col md:flex-row items-center justify-center gap-2">
                <span class="inline-flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse inline-block"></span> Konsultan Tersedia untuk Layanan Ini</span>
            </p>
        </div>
    </section>

    {{-- CONTENT SECTION --}}
    <section class="py-16 md:py-24 bg-white relative">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-2xl md:text-3xl font-serif text-brand-900 mb-4">Cakupan Keluhan / Kasus</h2>
                <div class="w-16 md:w-24 h-1 bg-gold-400 mx-auto mb-6 md:mb-8"></div>
                <div class="bg-slate-50 p-6 md:p-8 rounded-none border border-slate-200 text-left">
                    <p class="text-slate-700 text-base md:text-xl leading-relaxed mb-6 md:mb-8">
                        {{ $layanan['konten_lengkap'] }}
                    </p>
                    
                    <h4 class="text-base md:text-lg font-bold text-brand-900 mb-4 border-b border-slate-200 pb-2">Contoh Area Praktik:</h4>
                    <ul class="space-y-3">
                        @foreach($layanan['contoh_kasus'] as $kasus)
                        <li class="flex items-start gap-3 text-slate-700 text-sm md:text-base">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="leading-relaxed">{{ $kasus }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-12 md:mb-16">
                <div class="bg-brand-50 p-6 md:p-8 rounded-none border border-brand-100">
                    <h3 class="text-lg md:text-xl font-bold text-brand-900 mb-2 md:mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Privasi Klien Terjamin
                    </h3>
                    <p class="text-slate-600 text-sm md:text-base">Seluruh sesi konsultasi di CASP Indonesia menggunakan enkripsi tinggi dan dilindungi oleh kode etik menjaga kerahasiaan platform.</p>
                </div>
                <div class="bg-brand-50 p-6 md:p-8 rounded-none border border-brand-100">
                    <h3 class="text-lg md:text-xl font-bold text-brand-900 mb-2 md:mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Solusi Pakar Spesifik
                    </h3>
                    <p class="text-slate-600 text-sm md:text-base">Anda hanya akan dihubungkan dengan konsultan yang memang sudah memiliki jam terbang tinggi di kategori {{ $layanan['judul'] }}.</p>
                </div>
            </div>

            {{-- CTA SECTION --}}
            <div class="bg-brand-900 rounded-none p-8 md:p-14 text-center shadow-xl relative border border-brand-800 flex flex-col items-center">
                <div class="relative z-10 text-white w-full">
                    <h2 class="text-2xl md:text-4xl font-serif mb-3 md:mb-4">Siap untuk Konsultasi?</h2>
                    <p class="text-brand-300 font-light mb-8 md:mb-10 max-w-lg mx-auto text-sm md:text-base">
                        Dapatkan langkah hukum pasti dari perspektif pakar {{ $layanan['judul'] }}. Pesan sesi Anda sekarang.
                    </p>
                    <a href="{{ route('onboarding.index', ['bidang' => $layanan['judul']]) }}" class="inline-flex w-full md:w-auto items-center justify-center px-6 md:px-10 py-4 md:py-5 font-bold text-white bg-gold-500 rounded-none hover:bg-gold-600 shadow-[0_10px_20px_rgba(234,179,8,0.2)] transition-all">
                        Pesan Konsultasi Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
@endsection
