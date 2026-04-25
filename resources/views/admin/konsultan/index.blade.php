@extends('layouts.admin')

@section('title', 'Data Konsultan - Admin CASP Indonesia')
@section('page_title', 'Manajemen Konsultan')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Direktori Konsultan</h3>
            <p class="text-sm text-slate-500">Kelola ketersediaan, bidang hukum, dan profil pakar.</p>
        </div>
        <button onclick="document.getElementById('tambahKonsultanModal').classList.remove('hidden')" class="px-4 py-2 bg-brand-900 text-white font-medium rounded-lg text-sm hover:bg-brand-800 flex items-center gap-2 shadow-md transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah Konsultan Baru
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6 bg-slate-50/30">
        @forelse($konsultan as $item)
            @php
                $words = array_values(array_filter(array_map('trim', explode(' ', str_replace(['Dr.', 'S.H.', 'M.H.', 'M.Kn', ','], '', $item->nama)))));
                $initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 group relative">
                
                {{-- Status Badge --}}
                <div class="absolute top-4 right-4">
                    @if($item->status == 'aktif')
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    @else
                        <span class="flex h-3 w-3 relative">
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                    @endif
                </div>

                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-brand-50 border border-brand-100 flex items-center justify-center text-2xl font-bold text-brand-700 mb-4 group-hover:scale-105 transition-transform">
                        {{ $initials }}
                    </div>
                    
                    <h4 class="text-lg font-bold text-slate-800 mb-1 px-2 line-clamp-1" title="{{ $item->nama }}">{{ $item->nama }}</h4>
                    <p class="text-sm text-brand-600 font-medium mb-3">{{ $item->spesialisasi }}</p>
                    
                    <div class="w-full grid grid-cols-2 gap-2 text-center border-t border-slate-100 pt-3 mt-1">
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-0.5">Pengalaman</div>
                            <div class="text-sm font-semibold text-slate-700">{{ $item->pengalaman_tahun }} Tahun</div>
                        </div>
                        <div class="border-l border-slate-100">
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-0.5">Rating</div>
                            <div class="text-sm font-semibold text-amber-500 flex items-center justify-center gap-1">
                                5.0 <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ addslashes($item->gelar) }}', '{{ addslashes($item->spesialisasi) }}', {{ $item->pengalaman_tahun }}, '{{ addslashes($item->username) }}', '{{ addslashes($item->jadwal_shift ?? '') }}')" class="py-1.5 text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-md transition-colors">Edit Profil</button>
                    <form action="{{ route('admin.konsultan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konsultan ini? Data yang terhapus tidak dapat dikembalikan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-md transition-colors">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-slate-300">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h4 class="text-lg font-medium text-slate-800 mb-1">Data Kosong</h4>
                <p class="text-sm text-slate-500">Belum ada konsultan terdaftar di sistem.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH KONSULTAN --}}
<div id="tambahKonsultanModal" class="fixed inset-0 z-[100] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative inline-block w-full max-w-2xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:p-6" onclick="event.stopPropagation()">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="document.getElementById('tambahKonsultanModal').classList.add('hidden')" class="text-slate-400 bg-white rounded-md hover:text-slate-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="sm:flex sm:items-start mb-6">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-brand-50 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg font-bold leading-6 text-slate-900">Tambah Konsultan Baru</h3>
                    <p class="text-sm text-slate-500">Buat akun untuk konsultan baru. Setelah dibuat, konsultan bisa login dan melengkapi profil mereka sendiri.</p>
                </div>
            </div>

            <form action="{{ route('admin.konsultan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Cth: Budi Santoso">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Gelar</label>
                        <input type="text" name="gelar" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Cth: S.H., M.Kn">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Spesialisasi Utama *</label>
                        <input type="text" name="spesialisasi" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Cth: Hukum Bisnis">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pengalaman (Tahun) *</label>
                        <input type="number" min="0" name="pengalaman_tahun" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Cth: 5">
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Informasi Login</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Username *</label>
                            <input type="text" name="username" required autocomplete="off" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Cth: budi_s">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password *</label>
                            <div class="relative">
                                <input type="password" id="tambah_password" name="password" required minlength="6" autocomplete="new-password" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none pr-10" placeholder="Minimal 6 karakter">
                                <button type="button" onclick="togglePasswordVisibility('tambah_password', 'eyeTambah')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-brand-600 transition-colors focus:outline-none">
                                    <svg id="eyeTambah" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-brand-600 text-base font-medium text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Konsultan
                    </button>
                    <button type="button" onclick="document.getElementById('tambahKonsultanModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2 bg-white text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KONSULTAN --}}
<div id="editKonsultanModal" class="fixed inset-0 z-[100] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative inline-block w-full max-w-2xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:p-6" onclick="event.stopPropagation()">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="document.getElementById('editKonsultanModal').classList.add('hidden')" class="text-slate-400 bg-white rounded-md hover:text-slate-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="sm:flex sm:items-start mb-6">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-brand-50 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg font-bold leading-6 text-slate-900">Edit Data Konsultan</h3>
                    <p class="text-sm text-slate-500">Perbarui data atau atur ulang password akun konsultan ini.</p>
                </div>
            </div>

            <form id="formEditKonsultan" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" id="edit_nama" name="nama" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Gelar</label>
                        <input type="text" id="edit_gelar" name="gelar" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Spesialisasi Utama *</label>
                        <input type="text" id="edit_spesialisasi" name="spesialisasi" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pengalaman (Tahun) *</label>
                        <input type="number" min="0" id="edit_pengalaman" name="pengalaman_tahun" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Operasional / Shift (WIB)</label>
                        <input type="text" id="edit_jadwal_shift" name="jadwal_shift" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Contoh: 09:00 - 17:00">
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Informasi Login</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Username *</label>
                            <input type="text" id="edit_username" name="username" required autocomplete="off" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                            <div class="relative">
                                <input type="password" id="edit_password" name="password" minlength="6" autocomplete="new-password" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none pr-10" placeholder="Kosongkan jika tidak diubah">
                                <button type="button" onclick="togglePasswordVisibility('edit_password', 'eyeEdit')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-brand-600 transition-colors focus:outline-none">
                                    <svg id="eyeEdit" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Hanya isi jika ingin mengubah password.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-brand-600 text-base font-medium text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('editKonsultanModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2 bg-white text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, nama, gelar, spesialisasi, pengalaman, username, jadwal_shift) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_gelar').value = gelar;
        document.getElementById('edit_spesialisasi').value = spesialisasi;
        document.getElementById('edit_pengalaman').value = pengalaman;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_jadwal_shift').value = jadwal_shift || '';
        
        let form = document.getElementById('formEditKonsultan');
        form.action = '/admin/konsultan/' + id;
        
        document.getElementById('editKonsultanModal').classList.remove('hidden');
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
        } else {
            input.type = 'password';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }
    }
</script>
@endpush
@endsection
