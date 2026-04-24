@extends('layouts.app')

@section('title', 'Menunggu Pembayaran - CASP Indonesia')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
        {{-- Header --}}
        <div class="bg-brand-900 px-6 py-8 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-800 to-brand-900 opacity-90 z-0"></div>
            {{-- Decorative bg --}}
            <svg class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 text-white/5 w-64 h-64" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"></circle></svg>
            
            <div class="relative z-10">
                <p class="text-sm font-medium text-brand-100 mb-1 uppercase tracking-wider">Total Tagihan</p>
                <h2 class="text-4xl font-bold font-serif tabular-nums text-gold-400">
                    Rp {{ number_format($pembayaran->total, 0, ',', '.') }}
                </h2>
                <div class="mt-4 px-4 py-2 bg-white/10 rounded-lg inline-block border border-white/20">
                    <p class="text-xs text-brand-100 uppercase tracking-widest font-semibold">Batas Waktu Pembayaran</p>
                    <div id="countdown" class="text-2xl font-bold font-mono tracking-widest tabular-nums text-white mt-1">
                        03:00
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex justify-between items-center mb-4">
                <span class="text-slate-500 text-sm">Metode Pembayaran</span>
                <span class="font-bold text-slate-800 uppercase bg-white px-3 py-1 rounded shadow-sm border border-slate-200">
                    {{ $pembayaran->metode }}
                </span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-slate-500 text-sm">Order ID</span>
                <span class="font-medium text-slate-800 font-mono text-sm">{{ $pembayaran->id }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 text-sm">Status</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-amber-400 animate-pulse" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Menunggu Pembayaran
                </span>
            </div>
        </div>

        {{-- Action --}}
        <div class="px-6 py-6 bg-white">
            <p class="text-sm text-center text-slate-500 mb-6">
                Silakan selesaikan pembayaran menggunakan metode <strong class="uppercase text-slate-800">{{ $pembayaran->metode }}</strong> sebelum waktu habis.
            </p>
            
            <form action="{{ route('pembayaran.sukses', $pembayaran->id) }}" method="POST">
                @csrf
                <button type="submit" id="btn-bayar" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gold-500 hover:bg-gold-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 transition-all active:scale-[0.98]">
                    SAYA SUDAH BAYAR (SIMULASI)
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <a href="{{ route('landing') }}" class="text-xs text-rose-500 hover:text-rose-700 font-medium transition-colors">
                    Batalkan Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const d = document;
        let sisaDetik = {{ $sisaDetik }};
        const countdownEl = d.getElementById('countdown');
        const btnBayar = d.getElementById('btn-bayar');
        const invoiceId = '{{ $pembayaran->id }}';

        function pad(num) {
            return num.toString().padStart(2, '0');
        }

        function updateDisplay() {
            if (sisaDetik <= 0) {
                countdownEl.innerHTML = "00:00";
                countdownEl.classList.add('text-rose-500');
                btnBayar.disabled = true;
                btnBayar.classList.replace('bg-gold-500', 'bg-slate-300');
                btnBayar.classList.replace('hover:bg-gold-600', 'hover:bg-slate-300');
                btnBayar.innerText = 'WAKTU HABIS';
                
                // Trigger kadaluarsa
                fetch(`/pembayaran/${invoiceId}/kadaluarsa`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    setTimeout(() => {
                        window.location.href = "{{ route('landing') }}?error=expired";
                    }, 1500);
                });
                return;
            }

            const menit = Math.floor(sisaDetik / 60);
            const detik = sisaDetik % 60;
            countdownEl.innerHTML = pad(menit) + ':' + pad(detik);
        }

        updateDisplay();
        
        const timer = setInterval(() => {
            sisaDetik--;
            updateDisplay();
            if(sisaDetik <= 0) clearInterval(timer);
        }, 1000);
    });
</script>
@endpush
