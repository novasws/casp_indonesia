<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - CASP Indonesia')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:  ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"DM Serif Display"', 'serif'],
                        outfit: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        'brand': {
                            900: '#06162E',  // Sangat gelap / Midnight
                            800: '#0A2342',  // Navy utama
                            700: '#123364',
                            600: '#1A4A8A',
                            500: '#1E5EBF',  // Tombol aksi
                            400: '#3B82F6',
                            300: '#93C5FD',
                            100: '#DBEAFE',
                            50:  '#EFF6FF',
                        },
                        'gold': {
                            900: '#713f12',
                            700: '#a16207',
                            500: '#eab308',
                            400: '#facc15',
                            300: '#fde047',
                            100: '#fef9c3',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #F1F5F9; color: #0F172A; overflow-x: hidden; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        .sidebar-item.active {
            background-color: rgba(37, 99, 235, 0.1);
            color: #1E5EBF;
            border-right: 4px solid #1E5EBF;
            font-weight: 600;
        }

        /* Custom Global Override for Buttons (Radius 0 / Siku) */
        button,
        [type="submit"],
        [type="button"],
        a[class*="px-"][class*="py-"][class*="bg-"] {
            border-radius: 0px !important;
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden"></div>
    <aside id="admin-sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col hidden absolute md:relative z-50 h-full transform transition-transform duration-300 md:translate-x-0 md:flex shadow-xl md:shadow-sm">
        <div class="h-16 flex items-center px-6 border-b border-slate-100">
            <span class="text-2xl font-serif text-brand-900 italic tracking-wide">CASP<span class="text-gold-500">.</span>Admin</span>
        </div>
        
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.profil.edit') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.profil.edit') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Edit Profil
                </a>
                <div class="px-6 py-2 mt-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Manajemen</div>
                
                @if(auth()->user()->is_superadmin)
                <a href="{{ route('admin.keluhan.index') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.keluhan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Data Keluhan
                </a>
                @endif
                
                <a href="{{ route('admin.konsultasi.index') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.konsultasi.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                    Konsultasi
                </a>
                
                <a href="{{ route('admin.pembayaran.index') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    Pembayaran
                </a>
                
                @if(auth()->user()->is_superadmin)
                <a href="{{ route('admin.konsultan.index') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.konsultan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    Konsultan
                </a>
                <a href="{{ route('admin.konten.index') }}" class="sidebar-item flex items-center px-6 py-3 text-slate-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.konten.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Kelola Konten
                </a>
                @endif
                
            </nav>
        </div>
        
        <div class="p-4 border-t border-slate-100">
            <a href="{{ route('landing') }}" target="_blank" class="flex items-center text-sm font-medium text-slate-500 hover:text-brand-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                Lihat Website
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col relative overflow-hidden">
        {{-- TOPBAR --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button id="admin-menu-toggle" class="md:hidden text-slate-500 hover:text-brand-600 focus:outline-none mr-4 z-50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="text-slate-400 hover:text-brand-600 transition-colors relative flex items-center" id="notificationBtn" title="Notifikasi Konsultasi Aktif">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse transition-all duration-300 hidden"></span>
                    <span id="notifText" class="ml-2 text-xs font-bold text-rose-500 hidden bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100"></span>
                </button>
                
                <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                    @php $user = auth()->user(); @endphp
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-8 h-8 rounded-full object-cover shadow-sm border border-slate-200">
                    @else
                        <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-sm shadow-sm" style="background-color: {{ $user->warna_avatar ?? '#1E5EBF' }}">
                            {{ $user->inisial ?? 'AD' }}
                        </div>
                    @endif
                    <div class="hidden sm:block text-sm">
                        <div class="font-bold text-slate-800">{{ $user->nama ?? 'Admin' }}</div>
                        <div class="text-xs text-slate-500">{{ $user->is_superadmin ? 'Superadmin' : 'Konsultan' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ml-2 border-l border-slate-200 pl-4">
                        @csrf
                        <button type="submit" class="text-sm text-slate-500 hover:text-rose-600 font-semibold transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <div class="flex-1 overflow-y-auto p-6 md:p-8 relative">
            {{-- Background decorative --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 z-0 pointer-events-none"></div>
            
            <div class="relative z-10">
                @yield('content')
            </div>
        </div>
        {{-- Toast Notification --}}
        <div id="toast-notif" class="fixed bottom-6 right-6 bg-white border-l-4 border-emerald-500 rounded-lg shadow-xl p-4 flex items-start gap-4 transform translate-y-32 opacity-0 transition-all duration-500 z-50">
            <div class="bg-emerald-100 text-emerald-600 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </div>
            <div>
                <h4 class="text-slate-800 font-bold text-sm">Konsultasi Baru!</h4>
                <p id="toast-msg" class="text-slate-500 text-xs mt-1">Klien telah masuk ke ruang tunggu.</p>
                <div class="mt-2">
                    <a href="{{ route('admin.konsultasi.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Lihat Antrean →</a>
                </div>
            </div>
            <button onclick="hideToast()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

    </main>

    @stack('scripts')
    <script>
        // BGM Notif Array (using public domain short ping)
        const notifSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        const toastEl = document.getElementById('toast-notif');
        let currentCount = 0;
        let isFirstFetch = true;

        function showToast(msg) {
            document.getElementById('toast-msg').textContent = msg;
            toastEl.classList.remove('translate-y-32', 'opacity-0');
            notifSound.play().catch(e => console.log('Audio autoplay prevented'));
            setTimeout(hideToast, 8000);
        }

        function hideToast() {
            toastEl.classList.add('translate-y-32', 'opacity-0');
        }

        // Mobile Sidebar Toggle Logic
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('admin-menu-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('hidden');
            overlay.classList.toggle('hidden');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        document.addEventListener('DOMContentLoaded', function() {
            const notifBadge = document.getElementById('notifBadge');
            const notifText = document.getElementById('notifText');

            function fetchNotifications() {
                fetch('{{ route("admin.notifications") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        notifBadge.classList.remove('hidden');
                        notifText.classList.remove('hidden');
                        notifText.textContent = data.message;
                        
                        // Cek jika ada konsultasi baru dan bukan trigger pertama kali
                        if(data.count > currentCount && !isFirstFetch) {
                            showToast(data.message);
                        }
                    } else {
                        notifBadge.classList.add('hidden');
                        notifText.classList.add('hidden');
                        notifText.textContent = '';
                    }
                    currentCount = data.count;
                    isFirstFetch = false;
                })
                .catch(error => console.error('Error fetching notifications:', error));
            }

            // Fetch once on load
            fetchNotifications();

            // Poll every 5 seconds for faster notification delivery
            setInterval(fetchNotifications, 5000);
        });
    </script>
</body>
</html>
