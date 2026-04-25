@extends('layouts.admin')

@section('title', 'Dashboard - Admin CASP Indonesia')
@section('page_title', 'Overview Dashboard')

@section('content')
<div class="space-y-6">
    
    {{-- Welcome Banner --}}
    <div class="bg-brand-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl border border-brand-800">
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-600 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 opacity-30"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-gold-500 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 opacity-20"></div>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
            <div>
                <h1 class="text-3xl font-serif mb-2">Selamat datang kembali, Admin!</h1>
                <p class="text-brand-200">Berikut adalah rekapitulasi data platform konsultasi hukum CASP Indonesia hari ini.</p>
            </div>
            <div class="mt-6 md:mt-0 flex flex-col items-center md:items-end text-center md:text-right w-full md:w-auto">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap text-slate-800 items-center justify-center md:justify-end gap-2 mb-3 relative z-20 w-full">
                    <select name="month" class="rounded-lg border-none text-sm font-medium py-1.5 px-3 bg-white/90 hover:bg-white focus:ring-2 focus:ring-gold-500 transition-colors shadow-sm cursor-pointer flex-1 md:flex-none">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="rounded-lg border-none text-sm font-medium py-1.5 px-3 bg-white/90 hover:bg-white focus:ring-2 focus:ring-gold-500 transition-colors shadow-sm cursor-pointer flex-1 md:flex-none">
                        @foreach(range(date('Y') - 2, date('Y')) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gold-500 hover:bg-gold-400 text-brand-900 px-4 py-1.5 rounded-lg text-sm font-bold transition-colors shadow-sm w-full md:w-auto mt-2 md:mt-0">
                        Filter
                    </button>
                </form>
                <p class="text-sm text-brand-300 font-medium md:text-right">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-3xl font-bold text-gold-400 text-center md:text-right">{{ \Carbon\Carbon::now()->format('H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Card 1: Konsultasi Aktif --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                </div>
                <span class="text-sm font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md line-through">Sedang Berjalan</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Konsultasi Aktif</h3>
            <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['aktif_konsultasi'] ?? 0) }}</p>
            <div class="text-xs text-slate-500 mt-2">Dari total {{ number_format($stats['total_konsultasi'] ?? 0) }} riwayat konsultasi</div>
        </div>

        {{-- Card 2: Total Keluhan Pending --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <span class="text-sm font-semibold text-rose-600 bg-rose-50 px-2 py-1 rounded-md">Butuh Tindakan</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Keluhan Menunggu Analisis</h3>
            <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['pending_keluhan'] ?? 0) }}</p>
            <div class="text-xs text-slate-500 mt-2">Dari total {{ number_format($stats['total_keluhan'] ?? 0) }} keluhan masuk</div>
        </div>

        {{-- Card 3: Pendapatan --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-sm font-semibold text-brand-600 bg-brand-50 px-2 py-1 rounded-md">Total Sukses</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Volume Pembayaran</h3>
            <p class="text-3xl font-bold text-slate-900 mt-1">Rp {{ number_format($stats['total_pembayaran'] ?? 0, 0, ',', '.') }}</p>
            <div class="text-xs text-slate-500 mt-2">Sistem otomatis terupdate via Webhook</div>
        </div>

    </div>

    {{-- Analytical Charts (Khusus Superadmin) --}}
    @if(auth()->user()->is_superadmin && isset($chart_data))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-slate-800 text-sm font-bold mb-4">Tren Konsultasi (7 Hari Terakhir)</h3>
            <div class="relative w-full h-[300px]">
                <canvas id="barChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <h3 class="text-slate-800 text-sm font-bold mb-4">Distribusi Paket Terjual</h3>
            <div class="flex-1 min-h-[300px] flex items-center justify-center relative">
                <div class="relative w-full max-w-[300px] h-[300px]">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Two Columns Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Recent Activities --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                <button class="text-brand-600 text-sm font-semibold hover:text-brand-700">Lihat Semua</button>
            </div>
            <div class="p-6">
                @if(isset($recent_activities) && count($recent_activities) > 0)
                    <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                        @foreach($recent_activities as $activity)
                        <div class="relative pl-6">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-2 border-current {{ $activity['warna'] }}"></span>
                            <p class="text-sm font-bold text-slate-800">{{ $activity['deskripsi'] }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $activity['waktu'] }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500">
                        Belum ada aktivitas terbaru.
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Overview Konsultan --}}
        @if(auth()->user()->is_superadmin)
        <div class="bg-brand-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden flex flex-col justify-between">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-800/50 to-transparent"></div>
            <div class="relative z-10 flex-1">
                <h3 class="text-lg font-bold text-white mb-1">Status Konsultan</h3>
                <p class="text-sm text-brand-300 mb-6">Sekilas tentang pakar yang tersedia di platform saat ini.</p>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center text-2xl font-serif text-white border border-white/20">
                        {{ $stats['total_konsultan'] ?? 0 }}
                    </div>
                    <div>
                        <div class="text-lg font-bold">Total Terdaftar</div>
                        <div class="text-xs text-emerald-400 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Siap Menerima Kasus
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.konsultan.index') }}" class="relative z-10 mt-6 block w-full py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-center rounded-xl text-sm font-bold transition-colors">
                Kelola Database Konsultan &rarr;
            </a>
        </div>
        @else
        <div class="bg-brand-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden flex flex-col justify-between">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-800/50 to-transparent"></div>
            <div class="relative z-10 flex-1">
                <h3 class="text-lg font-bold text-white mb-1">Ruang Kerja Profesional</h3>
                <p class="text-sm text-brand-300 mb-6">Pastikan Anda memberikan pelayanan hukum terbaik untuk setiap klien yang berkonsultasi.</p>
                
                <div class="mt-4 p-4 bg-white/10 rounded-xl border border-white/20">
                    <div class="flex items-center text-emerald-400 font-bold mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tips Hari Ini
                    </div>
                    <p class="text-xs text-brand-100 italic">"Selalu jaga profesionalitas dan privasi obrolan klien. Berikan solusi yang konkrit dan dapat dipertanggungjawabkan."</p>
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
    @if(auth()->user()->is_superadmin && isset($chart_data))
        // Bar Chart setup
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_data['labels']) !!},
                datasets: [{
                    label: 'Jumlah Sesi',
                    data: {!! json_encode($chart_data['values']) !!},
                    backgroundColor: '#1E5EBF',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        // Pie Chart setup
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Paket Biasa (1)', 'Paket Urgent (2)', 'Paket Prioritas (3)'],
                datasets: [{
                    data: {!! json_encode($pie_data) !!},
                    backgroundColor: ['#3B82F6', '#eab308', '#0A2342'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    @endif

    // Toast Notification for Pending Queues
    document.addEventListener('DOMContentLoaded', () => {
        let pending = {{ $pendingCount ?? 0 }};
        if (pending > 0) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-6 right-6 z-50 transform transition-all duration-500 translate-x-12 opacity-0 flex items-center p-4 space-x-3 w-max max-w-sm text-slate-700 bg-white rounded-2xl shadow-2xl border border-amber-200 border-l-4 border-l-amber-500 cursor-pointer';
            toast.onclick = function() { window.location.href = "{{ route('admin.konsultasi.index') }}"; };
            toast.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-amber-500 bg-amber-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-slate-800">Antrean Tersedia!</h5>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Terdapat <b>${pending} klien</b> yang sedang menunggu Anda untuk memulai sesi.</p>
                </div>
                <button onclick="event.stopPropagation(); this.parentElement.remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-slate-600 rounded-lg p-1.5 hover:bg-slate-50 inline-flex h-8 w-8">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            `;
            document.body.appendChild(toast);
            
            // Slide in
            setTimeout(() => {
                toast.classList.remove('translate-x-12', 'opacity-0');
            }, 100);

            // Auto hide after 8s
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('translate-x-12', 'opacity-0');
                    setTimeout(() => toast.remove(), 500);
                }
            }, 8000);
        }
    });
</script>
@endpush
@endsection
