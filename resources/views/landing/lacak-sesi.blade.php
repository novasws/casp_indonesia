@extends('layouts.app')

@section('title', 'Lacak Sesi Konsultasi – CASP Indonesia')

@section('content')
    @include('partials.navbar', ['navType' => 'onboarding'])

    <div class="min-h-[calc(100vh-60px)] bg-slate-50 flex items-center justify-center p-6">
        <div class="bg-white max-w-md w-full rounded-2xl p-8 border border-slate-200 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM15 9h-6m6 0v6m0-6l-6 6" /></svg>
            </div>
            
            <h2 class="text-2xl font-serif text-brand-900 text-center mb-2">Lacak Sesi Konsultasi</h2>
            <p class="text-sm text-slate-500 text-center mb-8 leading-relaxed">Masukkan Kode Sesi yang telah Anda simpan dan Nomor HP terdaftar untuk kembali ke ruangan obrolan Anda yang tertunda.</p>

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm mb-6 font-medium text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('lacak.sesi.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Pilih Kode Sesi</label>
                    <input type="text" name="token_sesi" placeholder="Contoh: CASP-ABC123" required class="w-full bg-slate-50 border border-slate-200 px-4 py-3.5 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none font-mono placeholder:text-slate-400 uppercase transition-all">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Nomor HP Saat Mendaftar</label>
                    <input type="tel" name="no_hp" placeholder="Contoh: 08123456789" required class="w-full bg-slate-50 border border-slate-200 px-4 py-3.5 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none placeholder:text-slate-400 transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-800 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        Masuk ke Sesi <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                    <a href="{{ route('landing') }}" class="block w-full text-center text-sm font-semibold text-slate-500 hover:text-slate-800 mt-4 transition-colors">Batal & Kembali ke Beranda</a>
                </div>
            </form>
        </div>
    </div>
@endsection
