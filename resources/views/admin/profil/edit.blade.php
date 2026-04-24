@extends('layouts.admin')

@section('title', 'Edit Profil - Admin CASP Indonesia')
@section('page_title', 'Edit Profil Saya')

@section('content')

@if(session('success'))
<div id="successAlert" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    <span class="text-sm font-semibold">{{ session('success') }}</span>
</div>
<script>setTimeout(() => document.getElementById('successAlert')?.remove(), 4000);</script>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Preview Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-6">
            <div class="text-center">
                @if($user->foto)
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 shadow-md border-4 border-slate-50 overflow-hidden bg-slate-100">
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4 shadow-md border-4 border-slate-50" style="background-color: {{ $user->warna_avatar ?? '#1E5EBF' }}20; color: {{ $user->warna_avatar ?? '#1E5EBF' }}">
                        {{ $user->inisial ?? 'AD' }}
                    </div>
                @endif

                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $user->nama }}</h3>
                <p class="text-brand-600 font-semibold text-sm mb-1">{{ $user->bidang_hukum ?? $user->spesialisasi ?? '-' }}</p>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full mb-4 {{ $user->status == 'online' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} text-xs font-semibold">
                    <span class="relative flex h-2 w-2">
                      <span class="{{ $user->status == 'online' ? 'animate-ping' : '' }} absolute inline-flex h-full w-full rounded-full {{ $user->status == 'online' ? 'bg-emerald-400 opacity-75' : 'bg-amber-400 opacity-50' }}"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 {{ $user->status == 'online' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    </span>
                    {{ $user->status == 'online' ? 'Online' : ($user->status == 'sibuk' ? 'Sibuk' : 'Offline') }}
                </div>

                <div class="w-full h-px bg-slate-100 my-4"></div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div class="text-lg font-bold text-slate-800">{{ $user->pengalaman_tahun }} Thn</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pengalaman</div>
                    </div>
                    <div class="bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                        <div class="text-lg font-bold text-amber-600 flex justify-center items-center gap-1">
                            5.0 <svg class="w-3.5 h-3.5 fill-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <div class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Rating</div>
                    </div>
                </div>

                @if($user->quote)
                <div class="mt-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-600 italic leading-relaxed">"{{ $user->quote }}"</p>
                </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Preview Kartu di Landing Page</p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Edit Form --}}
    <div class="lg:col-span-2">
        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Bio & Quote --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center text-brand-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Profil Publik</h3>
                </div>
                <p class="text-sm text-slate-500 mb-5">Informasi ini akan ditampilkan pada halaman utama website saat pengunjung melihat detail profil Anda.</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bio / Deskripsi Diri</label>
                        <textarea name="bio" id="bio-input" rows="5" maxlength="3000" oninput="updateCounter('bio-input','bio-counter',3000)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all resize-none" placeholder="Contoh: Seorang praktisi hukum berpengalaman 10+ tahun di bidang Perdata dan Bisnis...">{{ old('bio', $user->bio) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-slate-400">Deskripsi tentang latar belakang, keahlian, dan pengalaman Anda.</p>
                            <span id="bio-counter" class="text-xs font-semibold text-slate-400">{{ strlen(old('bio', $user->bio ?? '')) }}/3000</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kutipan / Motto Profesional</label>
                        <textarea name="quote" id="quote-input" rows="3" maxlength="1000" oninput="updateCounter('quote-input','quote-counter',1000)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all resize-none" placeholder='Contoh: "Keadilan hanya bermakna ketika bisa diakses oleh semua kalangan."'>{{ old('quote', $user->quote) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-slate-400">Kutipan ini akan tampil di popup detail konsultan di landing page.</p>
                            <span id="quote-counter" class="text-xs font-semibold text-slate-400">{{ strlen(old('quote', $user->quote ?? '')) }}/1000</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Diri --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Data Diri</h3>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Foto Profil</label>
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    <p class="text-[10px] text-slate-400 mt-1">Maksimal 2MB. Format: JPG, PNG, WEBP.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Gelar</label>
                        <input type="text" name="gelar" value="{{ old('gelar', $user->gelar) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all" placeholder="S.H., M.H.">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bidang Hukum / Spesialisasi</label>
                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $user->spesialisasi) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pengalaman (Tahun)</label>
                        <input type="number" name="pengalaman_tahun" value="{{ old('pengalaman_tahun', $user->pengalaman_tahun) }}" min="0" max="50" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Ubah Kredensial Login --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center text-rose-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Ubah Kredensial Login</h3>
                    <span class="text-xs text-slate-400 font-medium">(Abaikan password jika tidak ingin diubah)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Username Login</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all pr-12" placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-brand-600 transition-colors focus:outline-none">
                                <svg id="eyeIcon1" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all pr-12" placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-brand-600 transition-colors focus:outline-none">
                                <svg id="eyeIcon2" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-brand-600 text-white font-bold text-sm rounded-lg hover:bg-brand-700 shadow-md hover:shadow-lg transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updateCounter(inputId, counterId, max) {
    const len = document.getElementById(inputId).value.length;
    const el  = document.getElementById(counterId);
    el.textContent = len + '/' + max;
    if (len >= max * 0.9) {
        el.className = 'text-xs font-semibold text-rose-500';
    } else if (len >= max * 0.7) {
        el.className = 'text-xs font-semibold text-amber-500';
    } else {
        el.className = 'text-xs font-semibold text-slate-400';
    }
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
