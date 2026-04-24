<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - CASP Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"DM Sans"', 'sans-serif'], serif: ['"DM Serif Display"', 'serif'] },
                    colors: { brand: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0A2342' }, gold: { 400: '#eab308', 500: '#ca8a04' } }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center font-sans antialiased bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
    <div class="fixed inset-0 bg-brand-900/95 z-0"></div>
    
    <div class="relative z-10 w-full max-w-md px-6">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-serif text-white flex items-center justify-center gap-2">
                CASP<span class="text-gold-400">.</span>Admin
            </h1>
            <p class="text-brand-100/70 text-sm mt-3 font-medium tracking-wide w-3/4 mx-auto leading-relaxed">Portal Manajemen Kasus & Konsultasi Legal Terpadu</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-6">Masuk ke Akun Anda</h2>
            
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/50 text-rose-200 px-4 py-3 rounded-xl text-sm mb-6 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-brand-100 mb-2" for="username">Username Konsultan</label>
                    <input type="text" id="username" name="username" class="w-full bg-brand-900/50 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent transition-all placeholder:text-white/30" placeholder="Contoh: ucok" required autofocus value="{{ old('username') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-brand-100 mb-2" for="password">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="w-full bg-brand-900/50 border border-white/10 text-white rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent transition-all placeholder:text-white/30" placeholder="••••••••" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-800 hover:text-black transition-colors focus:outline-none">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-brand-900/50 text-gold-500 focus:ring-gold-500/50 cursor-pointer">
                        <span class="text-sm font-medium text-brand-100">Ingat Saya</span>
                    </label>
                </div>
                <button type="submit" class="w-full bg-gold-500 hover:bg-gold-400 text-brand-900 font-bold text-sm py-3.5 rounded-xl transition-colors shadow-lg hover:shadow-gold-500/20 mt-2">
                    Akses Dasbor
                </button>
            </form>
        </div>
        
        <p class="text-center text-xs text-brand-100/50 mt-10 font-medium">
            &copy; 2026 CASP Indonesia. Hanya untuk kalangan internal.
        </p>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            if(type === 'text') {
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            } else {
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        });
    </script>
</body>
</html>
