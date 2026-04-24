@extends('layouts.admin')

@section('title', 'Kelola Konten Website - Admin CASP Indonesia')
@section('page_title', 'Kelola Konten Website')

@section('content')

{{-- Success Toast --}}
@if(session('success'))
<div id="successAlert" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3 animate-fade-in">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    <span class="text-sm font-semibold">{{ session('success') }}</span>
</div>
<script>setTimeout(() => document.getElementById('successAlert')?.remove(), 4000);</script>
@endif

{{-- TAB NAVIGATION --}}
<div class="mb-6">
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-0">
        <button onclick="switchTab('hero')" id="tab-hero" class="tab-btn px-5 py-3 text-sm font-bold transition-all border-b-2 border-transparent text-slate-500 hover:text-brand-600">
            🏠 Hero & Statistik
        </button>
        <button onclick="switchTab('layanan')" id="tab-layanan" class="tab-btn px-5 py-3 text-sm font-bold transition-all border-b-2 border-transparent text-slate-500 hover:text-brand-600">
            ⚖️ Layanan Hukum
        </button>
        <button onclick="switchTab('cara_kerja')" id="tab-cara_kerja" class="tab-btn px-5 py-3 text-sm font-bold transition-all border-b-2 border-transparent text-slate-500 hover:text-brand-600">
            📋 Alur Konsultasi
        </button>
        <button onclick="switchTab('konsultan')" id="tab-konsultan" class="tab-btn px-5 py-3 text-sm font-bold transition-all border-b-2 border-transparent text-slate-500 hover:text-brand-600">
            👤 Profil Konsultan
        </button>
    </div>
</div>

{{-- ===== TAB: HERO & STATISTIK ===== --}}
<div id="panel-hero" class="tab-panel hidden">
    <form action="{{ route('admin.konten.update') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Hero Section --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center text-brand-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Hero Section</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Badge / Label Atas</label>
                        <input type="text" name="hero_badge" value="{{ $contents['hero_badge'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Utama</label>
                        <input type="text" name="hero_judul" value="{{ $contents['hero_judul'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi</label>
                        <textarea name="hero_deskripsi" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all resize-none">{{ $contents['hero_deskripsi'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Statistik Section --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Statistik (Angka di Hero)</h3>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kasus Diselesaikan</label>
                            <input type="text" name="stat_kasus_selesai" value="{{ $contents['stat_kasus_selesai'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tingkat Kepuasan</label>
                            <input type="text" name="stat_kepuasan" value="{{ $contents['stat_kepuasan'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Konsultan Aktif</label>
                            <input type="text" name="stat_konsultan" value="{{ $contents['stat_konsultan'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tarif Mulai Dari</label>
                            <input type="text" name="stat_harga_mulai" value="{{ $contents['stat_harga_mulai'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white font-bold text-sm rounded-lg hover:bg-brand-700 shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- ===== TAB: LAYANAN HUKUM ===== --}}
<div id="panel-layanan" class="tab-panel hidden">
    <form action="{{ route('admin.konten.updateLayanan') }}" method="POST">
        @csrf
        <div class="space-y-6">
            @php $layananData = $contents['layanan'] ?? []; @endphp
            @foreach($layananData as $index => $item)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between cursor-pointer" onclick="toggleAccordion('layanan-{{ $index }}')">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $item['icon'] ?? '⚖️' }}</span>
                        <h3 class="text-base font-bold text-slate-800">{{ $item['judul'] ?? 'Layanan ' . ($index + 1) }}</h3>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 transform transition-transform" id="chevron-layanan-{{ $index }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="p-6 hidden" id="content-layanan-{{ $index }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Icon (Emoji)</label>
                            <input type="text" name="layanan[{{ $index }}][icon]" value="{{ $item['icon'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Layanan</label>
                            <input type="text" name="layanan[{{ $index }}][judul]" value="{{ $item['judul'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi Singkat (Tampil di Card)</label>
                        <textarea name="layanan[{{ $index }}][deskripsi]" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ $item['deskripsi'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Konten Lengkap (Halaman Detail)</label>
                        <textarea name="layanan[{{ $index }}][konten_lengkap]" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ $item['konten_lengkap'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Contoh Kasus (1 per baris)</label>
                        <textarea name="layanan[{{ $index }}][contoh_kasus_text]" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500 resize-none font-mono text-xs">{{ implode("\n", $item['contoh_kasus'] ?? []) }}</textarea>
                        <p class="text-xs text-slate-400 mt-1">Tulis satu contoh kasus per baris.</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white font-bold text-sm rounded-lg hover:bg-brand-700 shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Semua Layanan
            </button>
        </div>
    </form>
</div>

{{-- ===== TAB: CARA KERJA ===== --}}
<div id="panel-cara_kerja" class="tab-panel hidden">
    <form action="{{ route('admin.konten.updateCaraKerja') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Langkah-Langkah Alur Konsultasi</h3>
            </div>

            @php $caraKerjaData = $contents['cara_kerja'] ?? []; @endphp
            <div class="space-y-4">
                @foreach($caraKerjaData as $index => $step)
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-10 h-10 bg-brand-100 text-brand-700 rounded-full flex items-center justify-center font-bold text-sm shrink-0 mt-1">
                        {{ $step['num'] ?? $index + 1 }}
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="hidden" name="cara_kerja[{{ $index }}][num]" value="{{ $step['num'] ?? $index + 1 }}">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Judul Langkah</label>
                            <input type="text" name="cara_kerja[{{ $index }}][judul]" value="{{ $step['judul'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Deskripsi</label>
                            <input type="text" name="cara_kerja[{{ $index }}][desc]" value="{{ $step['desc'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white font-bold text-sm rounded-lg hover:bg-brand-700 shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Alur Konsultasi
            </button>
        </div>
    </form>
</div>

{{-- ===== TAB: PROFIL KONSULTAN ===== --}}
<div id="panel-konsultan" class="tab-panel hidden">
    <form action="{{ route('admin.konten.update') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Kutipan / Visi Misi Konsultan</h3>
            </div>
            <p class="text-sm text-slate-500 mb-4">Teks ini muncul di modal biodata konsultan pada landing page (kutipan profesional).</p>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kutipan Profesional</label>
                <textarea name="konsultan_quote" rows="5" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all resize-none">{{ $contents['konsultan_quote'] ?? '' }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Kutipan ini akan ditampilkan pada popup detail setiap konsultan di halaman utama.</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white font-bold text-sm rounded-lg hover:bg-brand-700 shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(name) {
        // Hide all panels
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        // Deactivate all tabs
        document.querySelectorAll('.tab-btn').forEach(t => {
            t.classList.remove('border-brand-600', 'text-brand-700');
            t.classList.add('border-transparent', 'text-slate-500');
        });
        // Show selected panel
        document.getElementById('panel-' + name).classList.remove('hidden');
        // Activate selected tab
        const tab = document.getElementById('tab-' + name);
        tab.classList.add('border-brand-600', 'text-brand-700');
        tab.classList.remove('border-transparent', 'text-slate-500');

        // Save to localStorage
        localStorage.setItem('konten_active_tab', name);
    }

    function toggleAccordion(id) {
        const content = document.getElementById('content-' + id);
        const chevron = document.getElementById('chevron-' + id);
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    // Initialize tab
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('konten_active_tab') || 'hero';
        switchTab(saved);
    });
</script>
@endpush
