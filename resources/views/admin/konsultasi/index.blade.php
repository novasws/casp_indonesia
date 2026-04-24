@extends('layouts.admin')

@section('title', 'Data Konsultasi - Admin CASP Indonesia')
@section('page_title', 'Manajemen Konsultasi')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Sesi Konsultasi</h3>
            <p class="text-sm text-slate-500">Pantau dan kelola sesi yang sedang berjalan atau riwayat.</p>
        </div>
        <form action="{{ route('admin.konsultasi.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="bulan" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2 outline-none">
                <option value="semua">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ ($currentBulan ?? 'semua') == sprintf('%02d', $m) || ($currentBulan ?? 'semua') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2 outline-none">
                <option value="semua">Semua Tahun</option>
                @php $currentY = date('Y'); @endphp
                @for($y = $currentY; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ ($currentTahun ?? 'semua') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2 outline-none">
                <option value="semua" {{ ($currentStatus ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                <option value="menunggu" {{ ($currentStatus ?? '') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="terjadwal" {{ ($currentStatus ?? '') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                <option value="berjalan" {{ ($currentStatus ?? '') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="selesai" {{ ($currentStatus ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            @if(request()->hasAny(['bulan', 'tahun', 'status']) && (request('bulan') != 'semua' || request('tahun') != 'semua' || request('status') != 'semua'))
                <a href="{{ route('admin.konsultasi.index') }}" class="px-3 py-2 bg-rose-50 text-rose-600 text-sm font-medium rounded-lg hover:bg-rose-100 transition-colors border border-rose-200" title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Floating Bulk Action Bar (Superadmin Only) --}}
    @if(auth()->user()->is_superadmin)
    <div id="bulkActionBar" class="hidden px-6 py-3 bg-gradient-to-r from-rose-50 to-rose-100 border-b border-rose-200 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-rose-200 text-rose-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span class="text-sm font-bold text-rose-800"><span id="selectedCount">0</span> item dipilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="clearSelection()" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">Batal Pilih</button>
                <button type="button" onclick="confirmBulkDelete()" class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-sm transition-all hover:shadow-md">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus Terpilih
                </button>
            </div>
        </div>
    </div>
    <form id="bulkDeleteForm" action="{{ route('admin.konsultasi.bulkDestroy') }}" method="POST" class="hidden">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>
    @endif
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    @if(auth()->user()->is_superadmin)
                    <th class="px-4 py-4 w-10">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 focus:ring-offset-0 cursor-pointer transition-colors">
                        </label>
                    </th>
                    @endif
                    <th class="px-6 py-4 font-semibold">Tgl / Waktu</th>
                    <th class="px-6 py-4 font-semibold">Nama Klien</th>
                    @if(auth()->user()->is_superadmin)
                        <th class="px-6 py-4 font-semibold">Konsultan Dituju</th>
                    @endif
                    <th class="px-6 py-4 font-semibold">Paket</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($konsultasi as $item)
                <tr class="hover:bg-slate-50/80 transition-colors" id="row-{{ $item->id }}">
                    @if(auth()->user()->is_superadmin)
                    <td class="px-4 py-4 w-10">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="bulk_ids[]" value="{{ $item->id }}" onchange="updateBulkSelection()" class="bulk-checkbox w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 focus:ring-offset-0 cursor-pointer transition-colors">
                        </label>
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-900 font-medium">{{ $item->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $item->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $item->klien_nama }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $item->klien_hp }}</div>
                    </td>
                    @if(auth()->user()->is_superadmin)
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($item->konsultan)
                                @php
                                    $initials = substr(str_replace(['Dr.', 'S.H.'], '', $item->konsultan->nama), 0, 2);
                                @endphp
                                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold mr-3 border border-brand-200">
                                    {{ trim($initials) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-800">{{ $item->konsultan->nama }}</div>
                                </div>
                            @else
                                <span class="text-sm text-slate-400 italic">Belum Dialokasikan</span>
                            @endif
                        </div>
                    </td>
                    @endif
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-slate-700">{{ $item->paket }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status == 'berjalan')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Berjalan
                            </span>
                        @elseif($item->status == 'selesai')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                Selesai
                            </span>
                        @elseif($item->status == 'terjadwal')
                            <div class="flex flex-col items-start gap-1">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    Terjadwal
                                </span>
                                @php $pos = $queuePositions[$item->id] ?? '-'; @endphp
                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">Antrean ke-{{ $pos }}</span>
                            </div>
                        @else
                            <div class="flex flex-col items-start gap-1">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    Menunggu
                                </span>
                                @php $pos = $queuePositions[$item->id] ?? '-'; @endphp
                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">Antrean ke-{{ $pos }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" 
                                onclick="openDetailModal('{{ e($item->klien_nama) }}', '{{ e($item->klien_email) }}', '{{ e($item->klien_hp) }}', '{{ e($item->bidang_hukum ?? '-') }}', '{{ addslashes(e(str_replace(["\r", "\n"], ' ', $item->deskripsi_keluhan ?? '-'))) }}', '{{ route('admin.konsultasi.chat', $item->id) }}')" 
                                class="inline-block px-4 py-2 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-800 text-sm font-semibold rounded-[3px] transition-colors border border-slate-200">Detail</button>
                            
                            @if(in_array($item->status, ['terjadwal', 'menunggu']))
                                @if(($queuePositions[$item->id] ?? 99) === 1 || auth()->user()->is_superadmin)
                                <form action="{{ route('admin.konsultasi.mulai', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-block px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-800 text-sm font-semibold rounded-[3px] transition-colors border border-emerald-100">Mulai Sesi &rarr;</button>
                                </form>
                                @else
                                <button type="button" disabled class="inline-block px-4 py-2 bg-slate-50 text-slate-400 text-sm font-semibold rounded-[3px] border border-slate-200 cursor-not-allowed opacity-70" title="Harap selesaikan Antrean ke-1 terlebih dahulu">Belum Giliran</button>
                                @endif
                            @else
                                <a href="{{ route('admin.konsultasi.chat', $item->id) }}" class="inline-block px-4 py-2 bg-brand-50 text-brand-600 hover:bg-brand-100 hover:text-brand-800 text-sm font-semibold rounded-[3px] transition-colors border border-brand-100">Buka Chat &rarr;</a>
                            @endif
                            
                            @if(auth()->user()->is_superadmin)
                            <form action="{{ route('admin.konsultasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Tindakan ini tidak dapat dibatalkan.\nApakah Anda yakin ingin menghapus data konsultasi ini beserta seluruh riwayat obrolannya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-800 text-sm font-semibold rounded-[3px] transition-colors border border-rose-100" title="Hapus Permanen">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->is_superadmin ? 7 : 6 }}" class="px-6 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        </div>
                        <h4 class="text-lg font-medium text-slate-800 mb-1">Belum ada sesi</h4>
                        <p class="text-sm text-slate-500">Belum ada data konsultasi terkait.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-[100vh] px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" onclick="closeDetailModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 border border-slate-100">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold leading-6 text-slate-800 font-serif" id="modal-title">Detail Konsultasi</h3>
                    <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Klien</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <span class="block text-xs text-slate-500">Nama Lengkap</span>
                                <span class="block text-sm font-semibold text-slate-800" id="detail-nama"></span>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-500">Nomor HP</span>
                                <span class="block text-sm font-semibold text-slate-800" id="detail-hp"></span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="block text-xs text-slate-500">Email</span>
                                <span class="block text-sm font-semibold text-slate-800" id="detail-email"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100/50">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Rincian Kasus</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="block text-xs text-slate-500">Bidang Hukum Terkait</span>
                                <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-semibold bg-brand-100 text-brand-700 border border-brand-200" id="detail-bidang"></span>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-500">Deskripsi Keluhan / Masalah</span>
                                <div class="mt-1 bg-white p-3 rounded-lg border border-slate-200 max-h-64 overflow-y-auto">
                                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap" id="detail-keluhan"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="closeDetailModal()" class="w-full sm:w-1/3 inline-flex justify-center px-4 py-3 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-transparent rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 transition-colors">
                    Tutup
                </button>
                <a id="btn-masuk-chat" href="#" class="w-full sm:w-2/3 inline-flex justify-center items-center gap-2 px-4 py-3 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 border border-transparent rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-600 transition-all">
                    Masuk ke Chat
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openDetailModal(nama, email, hp, bidang, keluhan, chatUrl) {
        document.getElementById('detail-nama').textContent = nama || '-';
        document.getElementById('detail-email').textContent = email || '-';
        document.getElementById('detail-hp').textContent = hp || '-';
        document.getElementById('detail-bidang').textContent = bidang || 'Umum / Tidak Ditentukan';
        document.getElementById('detail-keluhan').textContent = keluhan || 'Tidak ada deskripsi yang dilampirkan.';
        
        const btnChat = document.getElementById('btn-masuk-chat');
        if (chatUrl) {
            btnChat.href = chatUrl;
            btnChat.classList.remove('hidden');
        } else {
            btnChat.classList.add('hidden');
        }
        
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Toast Notification for Pending Queues
    document.addEventListener('DOMContentLoaded', () => {
        let pending = {{ $pendingCount ?? 0 }};
        if (pending > 0 && "{{ $currentStatus }}" === "semua") {
            const toast = document.createElement('div');
            toast.className = 'fixed top-6 right-6 z-50 transform transition-all duration-500 translate-x-12 opacity-0 flex items-center p-4 space-x-3 w-max max-w-sm text-slate-700 bg-white rounded-2xl shadow-2xl border border-amber-200 border-l-4 border-l-amber-500';
            toast.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-amber-500 bg-amber-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-slate-800">Antrean Tersedia!</h5>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Terdapat <b>${pending} klien</b> yang sedang menunggu Anda untuk memulai sesi.</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-slate-600 rounded-lg p-1.5 hover:bg-slate-50 inline-flex h-8 w-8">
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

    // ========== BULK DELETE LOGIC ==========
    function toggleSelectAll(el) {
        const checkboxes = document.querySelectorAll('.bulk-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = el.checked;
            highlightRow(cb);
        });
        updateBulkSelection();
    }

    function updateBulkSelection() {
        const checkboxes = document.querySelectorAll('.bulk-checkbox');
        const checked = document.querySelectorAll('.bulk-checkbox:checked');
        const bar = document.getElementById('bulkActionBar');
        const countEl = document.getElementById('selectedCount');
        const selectAll = document.getElementById('selectAll');

        if (!bar) return;

        if (checked.length > 0) {
            bar.classList.remove('hidden');
            countEl.textContent = checked.length;
        } else {
            bar.classList.add('hidden');
        }

        // Update select all state
        if (selectAll) {
            selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
            selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
        }

        // Highlight rows
        checkboxes.forEach(cb => highlightRow(cb));
    }

    function highlightRow(cb) {
        const row = cb.closest('tr');
        if (cb.checked) {
            row.classList.add('bg-rose-50/60');
            row.classList.remove('hover:bg-slate-50/80');
        } else {
            row.classList.remove('bg-rose-50/60');
            row.classList.add('hover:bg-slate-50/80');
        }
    }

    function clearSelection() {
        const selectAll = document.getElementById('selectAll');
        if (selectAll) selectAll.checked = false;
        document.querySelectorAll('.bulk-checkbox').forEach(cb => {
            cb.checked = false;
            highlightRow(cb);
        });
        updateBulkSelection();
    }

    function confirmBulkDelete() {
        const checked = document.querySelectorAll('.bulk-checkbox:checked');
        const count = checked.length;
        if (count === 0) return;

        const ok = confirm(`PERINGATAN: Tindakan ini tidak dapat dibatalkan.\n\nAnda akan menghapus ${count} data konsultasi beserta seluruh riwayat obrolannya.\n\nLanjutkan?`);
        if (!ok) return;

        const form = document.getElementById('bulkDeleteForm');
        const inputsDiv = document.getElementById('bulkDeleteInputs');
        inputsDiv.innerHTML = '';

        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            inputsDiv.appendChild(input);
        });

        form.classList.remove('hidden');
        form.submit();
    }

</script>
@endpush
