<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'CASP Indonesia – Konsultasi Hukum')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet"/>

    {{-- Tailwind CSS CDN (ganti dengan vite/mix untuk production) --}}
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
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                },
            },
        }
    </script>

    <style>
        :root {
            --blue-900: #06162E; --blue-800: #0A2342; --blue-700: #123364;
            --blue-600: #1E5EBF; --blue-500: #2563EB; --blue-400: #3B82F6;
            --blue-300: #93C5FD; --blue-200: #BFDBFE; --blue-100: #DBEAFE; --blue-50:  #EFF6FF;
            --gray-50: #F8FAFC; --gray-100: #F1F5F9; --gray-200: #E2E8F0;
            --gray-300: #CBD5E1; --gray-400: #94A3B8; --gray-500: #64748B;
            --gray-600: #475569; --gray-700: #334155; --gray-800: #1E293B; --gray-900: #0F172A;
        }
        
        body { background-color: #F8FAFC; color: #0F172A; overflow-x: hidden; }
        
        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* Animation Delays */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        
        .fade-in-up { opacity: 0; }

        @keyframes slideUpFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
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
<body class="antialiased selection:bg-brand-500 selection:text-white">

    @yield('content')

    @stack('scripts')
    
    <script>
        // Intersection Observer for Scroll Animations
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            });

            document.querySelectorAll('.fade-on-scroll').forEach((el) => {
                el.classList.add('opacity-0');
                observer.observe(el);
            });
        });

        // AUTO POPUP CS MODAL SETELAH 2 MENIT (120 Detik) (Telah Dinonaktifkan)
        /* setTimeout(() => {
            const csModal = document.getElementById('csKeluhanModal');
            if(csModal && csModal.classList.contains('hidden')) {
                csModal.classList.remove('hidden');
            }
        }, 120000); */

        document.addEventListener('DOMContentLoaded', () => {
            const csToken = sessionStorage.getItem('cs_keluhan_token');
            let csInterval = null;

            if (csToken && document.getElementById('keluhan-form')) {
                document.getElementById('keluhan-form').style.display = 'none';
                document.getElementById('keluhan-chat-room').classList.remove('hidden');
                // Cek langsung jika sudah limit saat pertama kali load
                if (getClientMsgCount() >= CS_MSG_LIMIT) {
                    showPremiumBanner();
                }
                fetchCSChat();
                csInterval = setInterval(fetchCSChat, 3000);
            } else if (!csToken) {
                // Tidak ada token — pastikan chat room tersembunyi & form tampil
                const chatRoom = document.getElementById('keluhan-chat-room');
                const keluhanForm = document.getElementById('keluhan-form');
                if (chatRoom && !chatRoom.classList.contains('hidden')) {
                    chatRoom.classList.add('hidden');
                }
                if (keluhanForm) keluhanForm.style.display = 'block';
            }

            // Fungsi Auto-Scroll ke bawah
            function scrollToBottom() {
                const box = document.getElementById('keluhan-chat-messages');
                if(box) box.scrollTop = box.scrollHeight;
            }
            // Toast notifikasi di dalam chat room (saat sesi diakhiri CS)
            function showCSToast(msg) {
                const box = document.getElementById('keluhan-chat-messages');
                if (!box) return;
                const toast = document.createElement('div');
                toast.className = 'flex justify-center my-2';
                toast.innerHTML = `<div class="bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-semibold px-4 py-2 rounded-xl shadow-sm text-center">${msg}</div>`;
                box.appendChild(toast);
                box.scrollTop = box.scrollHeight;
            }

            const CS_MSG_LIMIT = 5;

            // Ambil atau inisialisasi hitungan pesan klien dari sessionStorage
            function getClientMsgCount() {
                return parseInt(sessionStorage.getItem('cs_client_msg_count') || '0', 10);
            }
            function setClientMsgCount(n) {
                sessionStorage.setItem('cs_client_msg_count', String(n));
            }

            // Update tampilan counter
            function updateMsgCounter() {
                const counter = document.getElementById('cs-msg-counter');
                const count = getClientMsgCount();
                const remaining = CS_MSG_LIMIT - count;
                if (!counter) return;
                if (count >= CS_MSG_LIMIT) { counter.classList.add('hidden'); return; }
                if (count > 0) {
                    counter.textContent = remaining + ' pesan tersisa';
                    counter.classList.remove('hidden');
                    // Ubah warna ke merah jika sisa 1
                    if (remaining <= 1) {
                        counter.classList.remove('text-amber-600', 'bg-amber-50', 'border-amber-200');
                        counter.classList.add('text-rose-600', 'bg-rose-50', 'border-rose-200');
                    } else {
                        counter.classList.remove('text-rose-600', 'bg-rose-50', 'border-rose-200');
                        counter.classList.add('text-amber-600', 'bg-amber-50', 'border-amber-200');
                    }
                } else {
                    counter.classList.add('hidden');
                }
            }

            // Tampilkan banner premium dan kunci input
            function showPremiumBanner() {
                const banner = document.getElementById('cs-limit-banner');
                const inputArea = document.getElementById('cs-input-area');
                const input = document.getElementById('keluhan-chat-input');
                const send = document.getElementById('keluhan-chat-send');
                if (banner) {
                    banner.classList.remove('hidden');
                    banner.style.animation = 'slideUpFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards';
                }
                if (inputArea) inputArea.classList.add('hidden');
                if (input) input.disabled = true;
                if (send) send.disabled = true;
                const counter = document.getElementById('cs-msg-counter');
                if (counter) counter.classList.add('hidden');
            }

            // -------------------------------------------------------
            // PENDING MESSAGES: pesan yang dikirim tapi belum dikonfirmasi
            // server — mencegah bubble menghilang saat polling berjalan
            // -------------------------------------------------------
            let pendingMessages = []; // [{ isi: string }]

            function renderChatBox(serverPesans) {
                const box = document.getElementById('keluhan-chat-messages');
                if (!box) return;

                // Hapus pending yang sudah dikonfirmasi server
                const confirmedTexts = new Set(
                    serverPesans.filter(p => p.pengirim === 'klien').map(p => p.isi)
                );
                pendingMessages = pendingMessages.filter(pm => !confirmedTexts.has(pm.isi));

                // Bangun HTML dari data server
                let html = '';
                serverPesans.forEach(p => {
                    if (p.pengirim === 'klien') {
                        html += `<div class="flex justify-end"><div class="bg-brand-600 text-white px-4 py-2 rounded-2xl rounded-tr-none text-sm max-w-[85%] relative"><p>${p.isi}</p><span class="text-[10px] text-brand-200 absolute right-2 -bottom-4">${p.waktu}</span></div></div><div class="h-2"></div>`;
                    } else {
                        html += `<div class="flex justify-start"><div class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-2xl rounded-tl-none text-sm max-w-[85%] relative"><p>${p.isi}</p><span class="text-[10px] text-slate-400 absolute left-2 -bottom-4">${p.waktu}</span></div></div><div class="h-2"></div>`;
                    }
                });

                // Selalu tambahkan pending (yang belum dikonfirmasi) di akhir
                pendingMessages.forEach(pm => {
                    html += `<div class="flex justify-end"><div class="bg-brand-600 text-white px-4 py-2 rounded-2xl rounded-tr-none text-sm max-w-[85%] relative opacity-75"><p>${pm.isi}</p><span class="text-[10px] text-brand-200 absolute right-2 -bottom-4">mengirim...</span></div></div><div class="h-2"></div>`;
                });

                if (box.innerHTML !== html) {
                    box.innerHTML = html;
                    scrollToBottom();
                }
            }

            async function fetchCSChat() {
                const token = sessionStorage.getItem('cs_keluhan_token');
                if(!token) return;
                try {
                    let res = await fetch('/keluhan/' + token + '/fetch');
                    if (!res.ok) {
                        resetKeluhanChat();
                        return;
                    }
                    let data = await res.json();
                    if(data.success) {
                        const clientCount = data.pesan.filter(p => p.pengirim === 'klien').length;
                        if (clientCount > getClientMsgCount()) setClientMsgCount(clientCount);

                        renderChatBox(data.pesan);

                        // Cek limit dari data server
                        if (getClientMsgCount() >= CS_MSG_LIMIT) {
                            showPremiumBanner();
                        } else {
                            updateMsgCounter();
                        }
                        if(data.status === 'selesai') {
                            // Hentikan polling
                            clearInterval(csInterval);
                            csInterval = null;
                            sessionStorage.removeItem('cs_keluhan_token');
                            sessionStorage.removeItem('cs_client_msg_count');

                            // Tampilkan toast notifikasi
                            showCSToast('Sesi chat telah diakhiri oleh CS. Anda akan diarahkan ke form awal...');

                            // Auto-reset ke form awal setelah 3 detik
                            setTimeout(() => resetKeluhanChat(), 3000);
                        }
                    } else {
                        // Token tidak valid / sesi tidak ditemukan — reset bersih
                        sessionStorage.removeItem('cs_keluhan_token');
                        sessionStorage.removeItem('cs_client_msg_count');
                        clearInterval(csInterval);
                        csInterval = null;
                        resetKeluhanChat();
                    }
                } catch(e) {}
            }

            const sendBtn = document.getElementById('keluhan-chat-send');
            const chatInput = document.getElementById('keluhan-chat-input');

            async function sendChatMessage() {
                const token = sessionStorage.getItem('cs_keluhan_token');
                const val = chatInput ? chatInput.value.trim() : '';
                if(!val || !token) return;

                // Cek limit SEBELUM kirim
                if (getClientMsgCount() >= CS_MSG_LIMIT) { showPremiumBanner(); return; }

                if (chatInput) chatInput.value = '';

                // Naikkan counter & update badge
                const newCount = getClientMsgCount() + 1;
                setClientMsgCount(newCount);
                updateMsgCounter();
                
                // Masukkan ke pending lalu render LANGSUNG
                pendingMessages.push({ isi: val });
                const box = document.getElementById('keluhan-chat-messages');
                if (box) {
                    box.innerHTML += `<div class="flex justify-end"><div class="bg-brand-600 text-white px-4 py-2 rounded-2xl rounded-tr-none text-sm max-w-[85%] relative opacity-75"><p>${val}</p><span class="text-[10px] text-brand-200 absolute right-2 -bottom-4">mengirim...</span></div></div><div class="h-2"></div>`;
                    scrollToBottom();
                }

                // Kirim ke server
                try {
                    const formData = new FormData();
                    formData.append('isi', val);
                    await fetch('/keluhan/' + token + '/send', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                        body: formData
                    });
                } catch(e) {}

                if (newCount >= CS_MSG_LIMIT) {
                    setTimeout(() => showPremiumBanner(), 800);
                }
            }

            if(sendBtn) {
                sendBtn.addEventListener('click', sendChatMessage);
                if (chatInput) chatInput.addEventListener('keypress', function(e) {
                    if(e.key === 'Enter') sendChatMessage();
                });
            }

            window.resetKeluhanChat = function() {
                // Hentikan polling interval agar tidak re-trigger chat room
                if (csInterval) { clearInterval(csInterval); csInterval = null; }

                // Bersihkan pending messages
                pendingMessages = [];

                sessionStorage.removeItem('cs_keluhan_token');
                sessionStorage.removeItem('cs_client_msg_count');
                
                const chatRoom = document.getElementById('keluhan-chat-room');
                if (chatRoom) chatRoom.classList.add('hidden');
                
                const kelForm = document.getElementById('keluhan-form');
                if (kelForm) {
                    kelForm.style.display = 'block';
                    kelForm.reset();
                }
                
                const btnCont = document.getElementById('btn-mulai-baru-container');
                if(btnCont) btnCont.remove();

                // Reset banner & input area
                const banner = document.getElementById('cs-limit-banner');
                if(banner) banner.classList.add('hidden');
                const inputArea = document.getElementById('cs-input-area');
                if(inputArea) inputArea.classList.remove('hidden');
                const counter = document.getElementById('cs-msg-counter');
                if(counter) { counter.classList.add('hidden'); counter.textContent = ''; }

                const kelBtn = document.getElementById('keluhan-btn');
                if (kelBtn) {
                    kelBtn.innerHTML = 'Mulai Obrolan CS';
                    kelBtn.disabled = false;
                }
                
                const chatInputEl = document.getElementById('keluhan-chat-input');
                if (chatInputEl) {
                    chatInputEl.disabled = false;
                    chatInputEl.placeholder = "Ketik pesan Anda di sini...";
                }
                
                const sendBtnEl = document.getElementById('keluhan-chat-send');
                if (sendBtnEl) sendBtnEl.disabled = false;
                
                const chatMsgs = document.getElementById('keluhan-chat-messages');
                if (chatMsgs) chatMsgs.innerHTML = '';
            };

            window.openCSModal = function() {
                const csToken = sessionStorage.getItem('cs_keluhan_token');
                const msgCount = getClientMsgCount();
                
                // Jika sudah mencapai limit, atau ada error terselubung, 
                // kita otomatis reset agar mulai dari awal form (sesuai request)
                if (csToken && msgCount >= CS_MSG_LIMIT) {
                    resetKeluhanChat();
                }
                
                const modal = document.getElementById('csKeluhanModal');
                if (modal) modal.classList.remove('hidden');
            };

        // KELUHAN / CUSTOMER SERVICE MULAI LOGIC
            if (document.getElementById('keluhan-form')) {
                document.getElementById('keluhan-form').addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const btn     = document.getElementById('keluhan-btn');
                    const errBox  = document.getElementById('keluhan-error');
                    const agree   = document.getElementById('keluhan-agree');

                    if (!agree.checked) {
                        errBox.textContent = 'Harap setujui syarat dan ketentuan terlebih dahulu.';
                        errBox.style.display = 'block';
                        return;
                    }

                    btn.innerHTML = 'Memulai Sesi...';
                    btn.disabled  = true;
                    errBox.style.display = 'none';

                    try {
                        const formData = new FormData(this);
                        const res  = await fetch('/keluhan/start', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                            body: formData,
                        });
                        const data = await res.json();

                        if (data.success) {
                            sessionStorage.setItem('cs_keluhan_token', data.token);
                            this.style.display = 'none';
                            document.getElementById('keluhan-chat-room').classList.remove('hidden');
                            fetchCSChat();
                            csInterval = setInterval(fetchCSChat, 3000);
                        } else {
                            errBox.textContent = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Error internal.');
                            errBox.style.display = 'block';
                            btn.innerHTML = 'Mulai Obrolan CS';
                            btn.disabled = false;
                        }
                    } catch (err) {
                        errBox.textContent = 'Gagal mengakses server jaringan aman.';
                        errBox.style.display = 'block';
                        btn.innerHTML = 'Mulai Obrolan CS';
                        btn.disabled = false;
                    }
                });
            }
        });
    </script>

    @if(!request()->is('admin*'))
    <button onclick="openCSModal()" class="fixed bottom-6 right-6 z-50 w-16 h-16 bg-brand-600 rounded-full shadow-[0_0_20px_rgba(37,99,235,0.5)] flex items-center justify-center text-white hover:bg-brand-700 hover:scale-110 transition-transform group border-2 border-white">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
        <span class="absolute right-full mr-4 bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">Hubungi Bantuan CS</span>
    </button>

    <div id="csKeluhanModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white w-full max-w-xl rounded-[24px] shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="document.getElementById('csKeluhanModal').classList.add('hidden')" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 focus:outline-none bg-slate-100 hover:bg-slate-200 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="p-6 md:p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-brand-50 text-brand-600 flex items-center justify-center rounded-full mx-auto mb-4 border border-brand-100">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-serif text-brand-900 mb-1">Customer Service</h3>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Tim operasional CASP akan menindaklanjuti laporan atau keluhan Anda segera melalui WhatsApp / Email.</p>
                </div>
                
                <div id="keluhan-chat-room" class="hidden flex flex-col h-[65vh] max-h-[520px]">
                    <div class="flex justify-between items-center mb-3 px-1">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-bold text-slate-700">Terhubung dengan CS</span>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Counter sisa pesan --}}
                            <span id="cs-msg-counter" class="hidden text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg"></span>
                            <button type="button" onclick="if(confirm('Yakin ingin mengakhiri sesi chat ini?')) resetKeluhanChat()" class="flex items-center gap-1.5 text-[11px] font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors border border-rose-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tutup Chat
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto bg-slate-50/50 p-4 rounded-xl border border-slate-200" id="keluhan-chat-messages">
                        <!-- Messages render here -->
                    </div>

                    {{-- BANNER LIMIT PREMIUM (tersembunyi, muncul setelah 5 pesan) --}}
                    <div id="cs-limit-banner" class="hidden mt-3">
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-900 via-brand-800 to-brand-900 p-4 text-white shadow-xl border border-indigo-700/40">
                            {{-- Decorative orb --}}
                            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                            <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-amber-400/10 rounded-full blur-lg"></div>
                            <div class="relative z-10">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex-shrink-0 w-9 h-9 bg-amber-400/20 border border-amber-400/40 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-white leading-snug">Batas Chat Gratis Tercapai</p>
                                        <p class="text-[11px] text-indigo-300 mt-0.5">5 dari 5 pesan telah digunakan</p>
                                    </div>
                                </div>
                                <p class="text-[12px] text-indigo-100 leading-relaxed mb-3.5">
                                    Untuk melanjutkan diskusi lebih mendalam dengan <strong class="text-white">Konsultan Hukum Profesional</strong> kami secara langsung, silakan upgrade ke layanan <span class="text-amber-300 font-bold">Konsultasi Premium</span>.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="/konsultasi" onclick="document.getElementById('csKeluhanModal').classList.add('hidden')" class="flex-1 flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-indigo-900 font-bold text-[12px] py-2.5 px-4 rounded-xl transition-all shadow-md hover:shadow-amber-400/30">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/></svg>
                                        Mulai Konsultasi Premium
                                    </a>
                                    <button type="button" onclick="resetKeluhanChat()" class="flex-1 flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-[12px] py-2.5 px-4 rounded-xl transition-all border border-white/20">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Mulai Sesi Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cs-input-area" class="mt-4 relative flex items-center">
                        <input type="text" id="keluhan-chat-input" class="w-full pl-4 pr-12 py-3.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-[14px] focus:ring-2 focus:ring-brand-500/20 text-sm shadow-sm transition-all" placeholder="Ketik pesan Anda di sini..."/>
                        <button type="button" id="keluhan-chat-send" class="absolute right-2 text-brand-600 hover:text-brand-800 p-2 hover:bg-brand-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5 -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-[10px] text-slate-400 font-medium">Layanan ini direkam untuk peningkatan kualitas.</p>
                    </div>
                </div>

                <form id="keluhan-form" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-700 mb-1.5 block">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" placeholder="Cth: Budi Santoso" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 text-sm shadow-sm"/>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 mb-1.5 block">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" name="hp" placeholder="08xxxxxxxxxx" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 text-sm shadow-sm"/>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700 mb-1.5 block">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" placeholder="nama@email.com" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 text-sm shadow-sm"/>
                    </div>
                    
                    <input type="hidden" name="kategori" value="Bantuan Umum / Pusat Bantuan">
                    <input type="hidden" name="urgensi" value="Normal (1-3 hari)">
                    
                    <div>
                        <label class="text-xs font-bold text-slate-700 mb-1.5 block">Kendala / Pertanyaan <span class="text-rose-500">*</span></label>
                        <textarea name="isi" rows="4" placeholder="Misal: Saya kebingungan cara memulai antrean, atau ada error saat pembayaran..." required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 text-sm resize-none shadow-sm"></textarea>
                    </div>
                    <div class="flex items-start gap-2 pt-2">
                        <input type="checkbox" id="keluhan-agree" required class="mt-0.5 w-4 h-4 text-brand-600 bg-slate-100 border-slate-300 rounded focus:ring-brand-500 cursor-pointer"/>
                        <label for="keluhan-agree" class="text-[11px] font-medium text-slate-500 cursor-pointer w-[90%]">
                            Saya bertanggung jawab atas kebenaran data di atas dan setuju untuk dihubungi oleh tim CASP Indonesia.
                        </label>
                    </div>
                    
                    <div id="keluhan-error" style="display:none;" class="bg-rose-50 text-rose-700 px-3 py-2.5 rounded-lg text-xs font-medium border border-rose-200 mt-2"></div>
                    
                    <button type="submit" id="keluhan-btn" class="w-full mt-4 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl transition-all shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] text-sm">
                        Mulai Obrolan CS
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
    {{-- PUSAT PANDUAN (HELP CENTER) MODAL --}}
    <div id="tutorialModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center px-4 bg-slate-900/70 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white w-full max-w-5xl rounded-[24px] shadow-2xl relative overflow-hidden flex flex-col md:flex-row h-[85vh] md:max-h-[700px]">
            
            {{-- Close Button untuk Mobile (absolut di atas) & Desktop --}}
            <button onclick="document.getElementById('tutorialModal').classList.add('hidden')" class="absolute top-4 right-4 md:top-6 md:right-6 text-slate-400 hover:text-slate-600 focus:outline-none bg-slate-100/80 backdrop-blur hover:bg-slate-200 rounded-full w-9 h-9 flex items-center justify-center transition-colors z-20 shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Sidebar Kategori / Tabs --}}
            <div class="w-full md:w-[35%] lg:w-[30%] bg-slate-50 border-r border-slate-200 flex flex-col flex-shrink-0 z-10 shadow-[4px_0_15px_rgba(0,0,0,0.02)] h-auto md:h-full">
                <div class="p-6 border-b border-slate-200 bg-white">
                    <div class="w-10 h-10 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-serif text-2xl text-brand-900 leading-tight">Pusat Panduan</h3>
                    <p class="text-[13px] text-slate-500 mt-1 font-medium">Bantuan seputar layanan CASP</p>
                </div>
                
                {{-- Horizontal scroll on mobile, vertical on desktop --}}
                <div class="flex-1 overflow-auto p-4 md:p-5 flex md:flex-col gap-2 md:space-y-2 border-b md:border-b-0 border-slate-200 bg-slate-50">
                    <button onclick="hcSwitchTab('tab-konsultasi', this)" class="hc-tab-btn flex-shrink-0 md:flex-shrink w-auto md:w-full text-left px-5 py-3.5 rounded-[14px] text-sm font-bold flex items-center gap-3 transition-all duration-200 border border-transparent bg-brand-600 text-white shadow-md cursor-pointer">
                        <svg class="w-5 h-5 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> 
                        <span>Konsultasi</span>
                    </button>
                    <button onclick="hcSwitchTab('tab-antrean', this)" class="hc-tab-btn flex-shrink-0 md:flex-shrink w-auto md:w-full text-left px-5 py-3.5 rounded-[14px] text-sm font-bold flex items-center gap-3 transition-all duration-200 border border-slate-200/60 text-slate-600 hover:bg-slate-100 hover:text-slate-800 cursor-pointer">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                        <span>Antrean & Jadwal</span>
                    </button>
                    <button onclick="hcSwitchTab('tab-cs', this)" class="hc-tab-btn flex-shrink-0 md:flex-shrink w-auto md:w-full text-left px-5 py-3.5 rounded-[14px] text-sm font-bold flex items-center gap-3 transition-all duration-200 border border-slate-200/60 text-slate-600 hover:bg-slate-100 hover:text-slate-800 cursor-pointer">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg> 
                        <span>Customer Service</span>
                    </button>
                    <button onclick="hcSwitchTab('tab-lacak', this)" class="hc-tab-btn flex-shrink-0 md:flex-shrink w-auto md:w-full text-left px-5 py-3.5 rounded-[14px] text-sm font-bold flex items-center gap-3 transition-all duration-200 border border-slate-200/60 text-slate-600 hover:bg-slate-100 hover:text-slate-800 cursor-pointer">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> 
                        <span>Lacak Sesi</span>
                    </button>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="flex-1 bg-white overflow-y-auto p-6 md:p-10 relative">
                
                {{-- TAB 1: KONSULTASI --}}
                <div id="tab-konsultasi" class="hc-tab-content block animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-50 text-brand-700 rounded-full text-xs font-bold mb-4 border border-brand-100">
                        <span class="w-2 h-2 rounded-full bg-brand-500"></span> Layanan Hukum
                    </div>
                    <h2 class="text-3xl font-serif text-slate-900 mb-6 pb-4 border-b border-slate-100">Alur Memulai Konsultasi</h2>
                    
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-200">1</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-1">Isi Form Keluhan</h4>
                                <p class="text-sm text-slate-600 leading-relaxed">Pilih kategori masalah hukum Anda (misalnya Perdata, Pidana, atau Bisnis). Tuliskan masalah Anda secara singkat agar konsultan mengerti konteks permasalahannya sebelum sesi chat dimulai.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-200">2</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-1">Pilih Konsultan Profesional</h4>
                                <p class="text-sm text-slate-600 leading-relaxed">Sistem akan menampilkan daftar konsultan CASP. Pilih konsultan yang sedang bersatus <strong>Online</strong> untuk ditangani saat itu juga, atau yang sedang <strong>Offline</strong> untuk ditangani pada *shift* kerja mereka selanjutnya.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-200">3</div>
                            <div class="w-full">
                                <h4 class="text-base font-bold text-slate-900 mb-1">Pilih Paket & Pembayaran Aman</h4>
                                <p class="text-sm text-slate-600 leading-relaxed mb-3">Tersedia durasi paket sesuai kebutuhan Anda. CASP mendukung pembayaran otomatis 24/7 menggunakan QRIS maupun Virtual Account bank terkemuka.</p>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex gap-4 text-sm mt-2">
                                    <svg class="w-5 h-5 flex-shrink-0 text-brand-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-slate-600"><strong>Penting:</strong> Batas waktu pembayaran adalah 15 menit. Chat Room akan langsung otomatis terbuka seketika setelah pembayaran berhasil dikonfirmasi.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: ANTREAN --}}
                <div id="tab-antrean" class="hc-tab-content hidden animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold mb-4 border border-amber-100">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Mekanisme Antrean
                    </div>
                    <h2 class="text-3xl font-serif text-slate-900 mb-6 pb-4 border-b border-slate-100">Sistem Antrean & Penjadwalan</h2>
                    
                    <p class="text-sm text-slate-600 mb-8 leading-relaxed">Di CASP Indonesia, Anda dipastikan 100% ditangani oleh praktisi hukum secara *live* (bukan Bot / AI balasan otomatis). Oleh karena itu, terkadang diperlukan waktu tunggu jika konsultan kami sedang padat.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Konsultan Sibuk</h4>
                            <p class="text-sm text-slate-600 leading-relaxed">Jika konsultan pilihan Anda berstatus <em>Online</em> namun sedang melayani sesi orang lain, Anda akan dimasukkan ke tampilan antrean. Sistem *Live Countdown* akan muncul. Harap stand-by dan <strong>jangan menutup browser Anda</strong> hingga terhubung otomatis.</p>
                        </div>
                        <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Penjadwalan (Konsultan Offline)</h4>
                            <p class="text-sm text-slate-600 leading-relaxed">Apabila Anda memilih agen yang berstatus <em>Offline</em>, sesi Anda tetap dapat dipesan dan aman pada database. Sesi tidak akan hangus, dan ruang obrolan Anda akan ditempatkan pada antrean pertama saat agen yang bersangkutan login di jam kerjanya.</p>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: CS --}}
                <div id="tab-cs" class="hc-tab-content hidden animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold mb-4 border border-emerald-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Resolusi Konflik
                    </div>
                    <h2 class="text-3xl font-serif text-slate-900 mb-6 pb-4 border-b border-slate-100">Customer Service & Keluhan</h2>
                    
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">Bantuan Customer Service berfungsi layaknya pos satpam/resepsionis utama aplikasi. Digunakan secara eksklusif untuk <strong class="text-slate-800">Bantuan Teknis Aplikasi & Laporan Keamanan</strong>, BUKAN untuk Konsultasi Hukum.</p>

                    <div class="bg-indigo-900 rounded-2xl p-6 md:p-8 text-white relative overflow-hidden">
                        {{-- Motif BG abstract --}}
                        <svg class="absolute top-0 right-0 w-32 h-32 opacity-10 transform translate-x-4 -translate-y-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        
                        <h4 class="text-xl font-bold mb-3 relative z-10">Kapan Saya Harus Menggunakan CS?</h4>
                        <ul class="space-y-3 relative z-10">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-300 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-indigo-50">Mengalami kendala teknis (Situs lambat, Error pembayaran, Salah *Transfer* QRIS).</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-300 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-indigo-50">Melaporkan kelakuan staf / Konsultan Hukum yang bermasalah secara kode etik.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-300 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-indigo-50">Pertanyaan kerja sama atau penawaran *partnership* *Corporate/B2B*.</span>
                            </li>
                        </ul>
                    </div>

                    <p class="text-sm text-slate-500 mt-5 flex items-center justify-center gap-2 text-center bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        Temukan ikon Bantuan / Tanda Tanya melayang di bagian pojok kanan bawah layar untuk memanggil CS.
                    </p>
                </div>

                {{-- TAB 4: LACAK SESI --}}
                <div id="tab-lacak" class="hc-tab-content hidden animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-bold mb-4 border border-violet-100">
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span> Pelacakan Riwayat
                    </div>
                    <h2 class="text-3xl font-serif text-slate-900 mb-6 pb-4 border-b border-slate-100">Pencarian & Pelacakan Sesi</h2>
                    
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">Fitur <strong>"Lacak Sesi"</strong> memungkinkan Anda untuk mencari riwayat konsultasi masa lalu maupun mengecek status antrean sesi Anda (misalnya jika Anda tidak sengaja menutup browser sebelum sesi dimulai).</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-sm">
                            <div class="w-12 h-12 bg-fuchsia-50 text-fuchsia-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Pantau Status *Live*</h4>
                            <p class="text-sm text-slate-600 leading-relaxed">Masukkan Nomor WhatsApp yang Anda gunakan saat memesan konsultasi. Sistem akan menampilkan status terkini: apakah masih dalam <strong>Antrean</strong>, sedang <strong>Berlangsung</strong>, atau sudah <strong>Selesai</strong>.</p>
                        </div>
                        <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-sm">
                            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Riwayat & Kesimpulan</h4>
                            <p class="text-sm text-slate-600 leading-relaxed">Untuk sesi yang sudah berstatus <em>Selesai</em>, Anda dapat melihat kembali kesimpulan / resume saran hukum yang telah diberikan oleh konsultan kami sebagai rujukan Anda ke depannya.</p>
                        </div>
                    </div>

                    <div class="bg-violet-50 rounded-xl p-4 mt-6 border border-violet-100 flex items-start gap-3">
                        <svg class="w-5 h-5 text-violet-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-violet-800">
                            <strong>Privasi Terjamin:</strong> Demi keamanan data Anda, sistem hanya menampilkan riwayat apabila Nomor Telepon atau ID Sesi yang dicari cocok secara spesifik dengan *database secure* kami.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function hcSwitchTab(tabId, btnContext) {
            // 1. Sembunyikan semua tab konten
            document.querySelectorAll('.hc-tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            
            // 2. Tampilkan tab terkait
            const targetTab = document.getElementById(tabId);
            if(targetTab) {
                targetTab.classList.remove('hidden');
                targetTab.classList.add('block');
            }

            // 3. Reset warna semua tombol tab
            document.querySelectorAll('.hc-tab-btn').forEach(btn => {
                btn.className = 'hc-tab-btn flex-shrink-0 md:flex-shrink w-auto md:w-full text-left px-5 py-3.5 rounded-[14px] text-sm font-bold flex items-center gap-3 transition-all duration-200 border border-slate-200/60 text-slate-600 hover:bg-slate-100 hover:text-slate-800 cursor-pointer';
                const svg = btn.querySelector('svg');
                if(svg) svg.classList.replace('opacity-90', 'opacity-70');
            });

            // 4. Highlight tombol tab yang dipilih
            if(btnContext) {
                btnContext.className = 'hc-tab-btn flex-shrink-0 md:flex-shrink w-auto md:w-full text-left px-5 py-3.5 rounded-[14px] text-sm font-bold flex items-center gap-3 transition-all duration-200 border border-transparent bg-brand-600 text-white shadow-md cursor-pointer';
                const svg = btnContext.querySelector('svg');
                if(svg) svg.classList.replace('opacity-70', 'opacity-90');
            }
        }
    </script>
</body>
</html>