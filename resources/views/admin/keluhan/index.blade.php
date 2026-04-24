@extends('layouts.admin')

@section('title', 'Data Keluhan - Admin CASP Indonesia')
@section('page_title', 'Manajemen Keluhan')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Keluhan Masuk</h3>
            <p class="text-sm text-slate-500">Laporan dari onboarding klien baru.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" onclick="submitBulkDelete()" class="px-4 py-2 bg-rose-50 text-rose-600 font-medium rounded-lg text-sm border border-rose-100 hover:bg-rose-100 flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Hapus Terpilih
            </button>
            <div class="relative w-full md:w-64">
                <input type="text" placeholder="Cari tiket keluhan..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>
    </div>
    
    <form id="bulkDeleteForm" action="{{ route('admin.keluhan.bulkDestroy') }}" method="POST">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4 font-semibold w-12 text-center">
                            <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 font-semibold">Klien & Kontak</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($keluhan as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox rounded border-slate-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $item->nama }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $item->email }} &bull; {{ $item->hp }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status == 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Telah Dialokasikan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Evaluasi
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                            <a href="{{ route('admin.keluhan.chat', $item->id) }}" class="text-brand-600 hover:text-brand-800 text-sm font-semibold transition-colors">Tinjau / Balas</a>
                            <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-rose-500 hover:text-rose-700 text-sm font-semibold transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h4 class="text-lg font-medium text-slate-800 mb-1">Tidak ada data</h4>
                            <p class="text-sm text-slate-500">Belum ada keluhan masuk yang tercatat di sistem.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <span class="text-sm text-slate-500">Menampilkan seluruh data.</span>
    </div>
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
            alert('Pilih setidaknya satu keluhan untuk dihapus.');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} keluhan yang dipilih beserta riwayatnya?`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    // Single Delete Action
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus keluhan ini beserta riwayatnya?')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/keluhan/${id}`;
            form.submit();
        }
    }
</script>
@endsection
