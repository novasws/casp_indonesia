<nav class="bg-brand-900 text-white px-5 md:px-8 h-16 flex items-center justify-between sticky top-0 z-50 shadow-md">
    <a href="{{ route('landing') }}" class="flex items-center gap-3 no-underline">
        <img src="{{ asset('images/logo.png') }}" alt="Logo CASP" class="h-10 w-10 object-cover rounded-full shadow-sm ring-2 ring-brand-700/50"/>
        <span class="font-serif text-xl md:text-2xl tracking-wide text-white flex flex-col leading-none">
            CASP <span class="text-[0.65rem] md:text-xs font-sans text-brand-300 tracking-widest uppercase mt-0.5">Indonesia</span>
        </span>
    </a>

    <!-- Hamburger menu button (Mobile) -->
    <button id="mobile-menu-btn" class="md:hidden block text-brand-200 hover:text-white focus:outline-none">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
    </button>

    <!-- Desktop Navigation -->
    <div class="hidden md:flex gap-6 items-center">
        @if($navType === 'landing')
            <a href="{{ url('/') }}" class="text-sm font-medium text-brand-200 hover:text-white transition-colors">Tentang Kami</a>
            <a href="{{ url('/') }}#konsultan-section" class="text-sm font-medium text-brand-200 hover:text-white transition-colors">Konsultan</a>
            <a href="{{ url('/') }}#layanan-section" class="text-sm font-medium text-brand-200 hover:text-white transition-colors">Layanan</a>
            <button onclick="document.getElementById('tutorialModal').classList.remove('hidden')" class="text-sm font-bold text-sky-300 hover:text-sky-200 transition-colors ml-2 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Panduan
            </button>
            <a href="{{ route('lacak.sesi') }}" class="text-sm font-bold text-amber-400 hover:text-amber-300 transition-colors ml-2 bg-amber-400/10 hover:bg-amber-400/20 px-3 py-1.5 rounded-lg border border-amber-400/20">Lacak Sesi &rarr;</a>
            <a href="{{ url('/') }}#complaint-form" class="text-sm font-bold text-gold-400 hover:text-gold-300 transition-colors bg-white/10 px-4 py-2 rounded-lg ml-2 hover:bg-white/20">Ajukan Keluhan</a>
        @elseif($navType === 'onboarding')
            <button onclick="document.getElementById('tutorialModal').classList.remove('hidden')" class="text-sm font-bold text-sky-300 hover:text-sky-200 transition-colors mr-4 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Panduan
            </button>
            <span class="text-sm text-slate-400">Layanan Hukum</span>
        @endif
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobile-menu" class="hidden absolute top-16 left-0 w-full bg-brand-900 border-t border-brand-800 md:hidden flex-col items-start py-4 shadow-xl">
        @if($navType === 'landing')
            <a href="{{ url('/') }}" class="text-base font-medium text-brand-200 hover:text-white hover:bg-brand-800 w-full text-left px-6 py-3 mobile-link">Tentang Kami</a>
            <a href="{{ url('/') }}#konsultan-section" class="text-base font-medium text-brand-200 hover:text-white hover:bg-brand-800 w-full text-left px-6 py-3 mobile-link">Daftar Konsultan</a>
            <a href="{{ url('/') }}#layanan-section" class="text-base font-medium text-brand-200 hover:text-white hover:bg-brand-800 w-full text-left px-6 py-3 mobile-link">Layanan Kami</a>
            <button onclick="document.getElementById('tutorialModal').classList.remove('hidden'); document.getElementById('mobile-menu').classList.add('hidden'); document.getElementById('mobile-menu').classList.remove('flex');" class="text-base font-bold text-sky-300 hover:text-white hover:bg-brand-800 w-full text-left px-6 py-3 mobile-link flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Panduan Penggunaan CASP
            </button>
            <a href="{{ route('lacak.sesi') }}" class="text-base font-bold text-amber-400 hover:text-amber-300 hover:bg-brand-800 w-full text-left px-6 py-3 mobile-link">Lacak Sesi (Bagi Guest)</a>
            <a href="{{ url('/') }}#complaint-form" class="text-base font-bold text-gold-400 hover:bg-brand-800 w-full text-left px-6 py-3 bg-white/5 border-t border-brand-800 mt-2 mobile-link">Ajukan Keluhan Keamanan</a>
        @elseif($navType === 'onboarding')
            <button onclick="document.getElementById('tutorialModal').classList.remove('hidden'); document.getElementById('mobile-menu').classList.add('hidden'); document.getElementById('mobile-menu').classList.remove('flex');" class="text-base font-bold text-sky-300 hover:text-white hover:bg-brand-800 w-full text-left px-6 py-3 mobile-link flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Panduan
            </button>
            <span class="text-sm text-slate-400 px-6 py-3 w-full border-t border-brand-800 mt-2">Layanan Hukum</span>
        @endif
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const links = document.querySelectorAll('.mobile-link');
        
        if(btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                menu.classList.toggle('flex');
            });

            links.forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    menu.classList.remove('flex');
                });
            });
        }
    });
</script>