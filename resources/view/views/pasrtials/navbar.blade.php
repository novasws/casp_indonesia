<nav style="background:var(--blue-900);color:white;padding:0 2rem;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50">
    <a href="{{ route('landing') }}" style="font-family:'DM Serif Display',serif;font-size:1.35rem;letter-spacing:.02em;color:white;text-decoration:none">
        CASP <span style="color:var(--blue-300)">Indonesia</span>
    </a>

    <div style="display:flex;gap:24px;align-items:center">
        @if($navType === 'landing')
            <span style="font-size:.85rem;font-weight:500;color:#BFDBFE;cursor:pointer" class="nav-link">Tentang Kami</span>
            <span style="font-size:.85rem;font-weight:500;color:#BFDBFE;cursor:pointer" class="nav-link">Konsultan</span>
            <span style="font-size:.85rem;font-weight:500;color:#BFDBFE;cursor:pointer" class="nav-link">Layanan</span>
            <a href="{{ route('onboarding.index') }}" style="font-size:.85rem;font-weight:500;color:#93C5FD;cursor:pointer;text-decoration:none" class="nav-link">Ajukan Keluhan</a>
        @elseif($navType === 'onboarding')
            <span style="font-size:.8rem;color:#94A3B8">Layanan Hukum</span>
        @endif
    </div>
</nav>

<style>
.nav-link:hover { color: white !important; }
</style>