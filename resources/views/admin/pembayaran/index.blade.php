@extends('layouts.admin')

@section('title', 'Data Pembayaran - Admin CASP Indonesia')
@section('page_title', 'Mutasi & Pembayaran')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Riwayat Pembayaran Klien</h3>
            <p class="text-sm text-slate-500">Log transaksi otomatis dari Midtrans / Gateway.</p>
        </div>
        <div class="flex flex-col xl:flex-row items-center gap-3 w-full md:w-auto">
            <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <select name="bulan" onchange="this.form.submit()" class="w-full sm:w-auto pl-3 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all cursor-pointer">
                    <option value="semua" {{ $currentBulan == 'semua' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        @php $mPad = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $mPad }}" {{ $currentBulan == $mPad ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" onchange="this.form.submit()" class="w-full sm:w-auto pl-3 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all cursor-pointer">
                    <option value="semua" {{ $currentTahun == 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                    @php $startYear = 2024; $currentY = date('Y'); @endphp
                    @for($y = $currentY; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ $currentTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto pl-3 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all cursor-pointer">
                    <option value="semua" {{ $currentStatus == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="lunas" {{ $currentStatus == 'lunas' ? 'selected' : '' }}>Berhasil / Lunas</option>
                    <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="expire" {{ $currentStatus == 'expire' ? 'selected' : '' }}>Gagal / Kedaluwarsa</option>
                </select>
            </form>
            <div class="flex items-center gap-2 w-full sm:w-auto mt-2 xl:mt-0 justify-end">
                <button type="button" onclick="submitBulkDelete()" class="px-4 py-2 bg-rose-50 text-rose-600 font-medium rounded-lg text-sm border border-rose-100 hover:bg-rose-100 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus
                </button>
                <a href="{{ route('admin.pembayaran.export', ['status' => $currentStatus, 'bulan' => $currentBulan, 'tahun' => $currentTahun]) }}" class="px-4 py-2 bg-brand-600 text-white font-medium rounded-lg text-sm hover:bg-brand-700 flex items-center gap-2 shadow-sm transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <form id="bulkDeleteForm" action="{{ route('admin.pembayaran.bulkDestroy') }}" method="POST">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4 font-semibold w-12 text-center">
                            <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 font-semibold">Order ID / Ref</th>
                        <th class="px-6 py-4 font-semibold">Terkait Konsultasi</th>
                        <th class="px-6 py-4 font-semibold">Metode</th>
                        <th class="px-6 py-4 font-semibold text-right">Jumlah (Rp)</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembayaran as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox rounded border-slate-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 font-mono text-sm">{{ $item->order_id }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->konsultasi)
                                <div class="text-sm font-semibold text-slate-800">{{ $item->konsultasi->nama_klien }}</div>
                                <div class="text-xs text-brand-600 mt-0.5">Sesi #{{ $item->konsultasi_id }}</div>
                            @else
                                <div class="text-sm text-slate-400 italic">Data terhapus</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center px-2 py-1 rounded bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700 uppercase">
                                {{ $item->metode ?? 'Qris/Virtual Acc' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="font-bold text-slate-900">{{ number_format($item->total, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status == 'lunas')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Berhasil
                                </span>
                            @elseif($item->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Menunggu Bayar
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    Gagal/Expired
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-rose-500 hover:text-rose-700 text-sm font-semibold transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                            <h4 class="text-lg font-medium text-slate-800 mb-1">Transaksi Kosong</h4>
                            <p class="text-sm text-slate-500">Belum ada riwayat pembayaran yang tercatat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

{{-- Single Delete Form Hidden --}}
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    // Check All Checkbox Logic
    const checkAll = document.getElementById('checkAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    if(checkAll) {
        checkAll.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
            checkAll.checked = allChecked;
            checkAll.indeterminate = someChecked && !allChecked;
        });
    });

    // Bulk Delete Action
    function submitBulkDelete() {
        const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
        if (checkedCount === 0) {
            alert('Pilih setidaknya satu data pembayaran untuk dihapus.');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} data pembayaran yang dipilih?`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    // Single Delete Action
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/pembayaran/${id}`;
            form.submit();
        }
    }
</script>
@endsection
