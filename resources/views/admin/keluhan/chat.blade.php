@extends('layouts.admin')

@section('title', 'Tinjau Keluhan - Admin CASP Indonesia')
@section('page_title', 'Ruang Percakapan Customer Service')

@section('content')

@php
    $isAudit = false; // Tidak ada mode audit di keluhan, cs yang menangani ini
@endphp

<div class="flex flex-col h-[calc(100vh-4rem)] -m-6 md:-m-8 overflow-hidden bg-slate-50 relative z-20">
    {{-- Top Info Bar --}}
    <div class="bg-white px-6 py-4 border-b border-slate-200 shadow-sm flex items-center justify-between shrink-0 relative z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.keluhan.index') }}" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-brand-600 hover:bg-brand-50 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h3 class="text-lg font-bold text-slate-800">{{ $keluhan->nama }}</h3>
                <div class="text-xs font-semibold text-brand-600">Email: {{ $keluhan->email }} | WA: {{ $keluhan->hp }}</div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($keluhan->status == 'diproses' || $keluhan->status == 'menunggu')
                <div class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold border border-emerald-100 flex items-center gap-2 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Berlangsung
                </div>
            @else
                <div class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold border border-slate-200">
                    Selesai
                </div>
            @endif
            @if($keluhan->status != 'selesai')
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.keluhan.selesai', $keluhan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menutup dan menyelesaikan tiket keluhan ini secara permanen?')">
                    @csrf
                    <button type="submit" class="px-4 py-1.5 bg-brand-900 text-white rounded-[3px] text-xs font-bold hover:bg-brand-800 transition-colors shadow-sm">Selesaikan Tiket</button>
                </form>
            </div>
            @endif
        </div>
    </div>

    {{-- Detail Keluhan Accordion --}}
    <div class="bg-white border-b border-slate-200 shrink-0 z-10 relative">
        <button onclick="toggleDetail()" class="w-full px-6 py-3 flex items-center justify-between text-left hover:bg-slate-50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shadow-sm group-hover:bg-brand-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Detail Laporan Kendala</h4>
                    <p class="text-xs text-slate-500 font-medium">Kategori: <span class="text-brand-600">{{ $keluhan->kategori }}</span></p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 group-hover:text-brand-600 transition-colors">
                <span id="detail-text">Tampilkan</span>
                <svg id="detail-icon" class="w-5 h-5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
        </button>
        <div id="detail-content" class="px-6 pb-5 pt-1 hidden">
            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200 shadow-inner relative max-h-48 overflow-y-auto">
                <h5 class="text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-widest">Pesan Pertama Kendala:</h5>
                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $keluhan->isi ?: 'Tidak ada pesan kendala.' }}</p>
            </div>
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 overflow-y-auto min-h-0 p-6 bg-slate-50 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] relative">
        <div class="absolute inset-0 bg-slate-50/90 z-0"></div>
        <div class="relative z-10 space-y-6 max-w-4xl mx-auto flex flex-col min-h-full" id="chat-messages">
            
            <div class="text-center mt-auto pt-8">
                <span class="px-3 py-1 bg-white border border-slate-200 text-slate-400 text-xs font-semibold rounded-full shadow-sm">
                    Sesi dibuka pada {{ $keluhan->created_at->format('d M Y, H:i') }}
                </span>
            </div>

            {{-- Initial / Dummy area to be replaced by AJAX --}}
            
        </div>
        {{-- Padding for scroll --}}
        <div id="chat-bottom"></div>
    </div>

    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    {{-- Input Area --}}
    <div class="bg-white p-4 border-t border-slate-200 shrink-0 relative z-10">
        <div class="max-w-4xl mx-auto flex gap-3 relative">
            @if($isAudit)
                <div class="flex-1 rounded-[3px] bg-slate-50 border border-slate-200 px-4 py-3 text-center text-slate-400 text-sm font-semibold italic">
                    Interaksi dinonaktifkan dalam Mode Audit.
                </div>
            @else
                <button type="button" class="w-12 shrink-0 bg-slate-50 border border-slate-200 rounded-[3px] hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                </button>
                <textarea id="chatInput" rows="1" placeholder="Ketik balasan Anda ke klien..." class="flex-1 bg-slate-50 border border-slate-200 rounded-[3px] px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none transition-all placeholder:text-slate-400" required></textarea>
                <button onclick="sendMessage()" class="shrink-0 bg-brand-600 hover:bg-brand-700 text-white rounded-[3px] px-6 py-3 text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <span>Kirim</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    const NAMA_KLIEN  = "{!! addslashes($keluhan->nama) !!}";
    const IN_KLIEN    = "{!! addslashes(substr($keluhan->nama, 0, 2)) !!}";
    const URL_FETCH   = '{{ route('admin.keluhan.fetchPesan', $keluhan->id) }}';
    const URL_KIRIM   = '{{ route('admin.keluhan.reply', $keluhan->id) }}';
    const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

    let renderedIds = [];

    // No timer code needed for Keluhan

    // --- Auto Resize Textarea ---
    const tx = document.getElementById('chatInput');
    if (tx) {
        tx.setAttribute('style', 'height:' + (tx.scrollHeight) + 'px;overflow-y:hidden;');
        tx.addEventListener("input", function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }, false);

        tx.addEventListener("keydown", function(e) {
            if(e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });
    }

    // --- Retrieve Msgs AJAX Polling ---
    async function fetchMessages() {
        try {
            const res = await fetch(URL_FETCH);
            const data = await res.json();
            if(data.success) {
                let isNew = false;
                data.pesan.forEach(p => {
                    if(!renderedIds.includes(p.id)) {
                        appendMessage(p);
                        renderedIds.push(p.id);
                        isNew = true;
                    }
                });
                if(isNew) {
                    document.getElementById('chat-bottom').scrollIntoView({behavior: "smooth"});
                }
            }
        } catch(e) {}
    }

    // Initial fetch, then poll every 3 seconds
    fetchMessages();
    setInterval(fetchMessages, 3000);

    // --- Send Message ---
    async function sendMessage() {
        const text = tx.value.trim();
        if(!text) return;

        // Optimistic UI append
        const tempId = 'temp-' + Date.now();
        appendMessage({id: tempId, pengirim: 'konsultan', isi: text, waktu: '...'});
        renderedIds.push(tempId);
        
        tx.value = '';
        tx.style.height = '48px';
        document.getElementById('chat-bottom').scrollIntoView({behavior: "smooth"});

        try {
            const res = await fetch(URL_KIRIM, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ isi: text })
            });
            const data = await res.json(); 
            if(data.success) {
                renderedIds.push(data.pesan.id); // Cegah double render
                fetchMessages(); 
            }
        } catch(e) {
            fetchMessages();
        }
    }

    // --- DOM Append ---
    const container = document.getElementById('chat-messages');

    function appendMessage(p) {
        const div = document.createElement('div');
        
        if(p.pengirim === 'klien') {
            div.className = "flex items-start gap-4 fade-in mt-4";
            div.innerHTML = `
                <div class="w-10 h-10 shrink-0 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold border border-slate-300 shadow-sm text-sm uppercase">
                    ${escapeHTML(IN_KLIEN)}
                </div>
                <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-sm max-w-[80%] shadow-sm relative group">
                    <h4 class="text-xs font-bold text-slate-900 mb-1">${escapeHTML(NAMA_KLIEN)} <span class="text-slate-400 font-normal ml-2">${p.waktu}</span></h4>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">${escapeHTML(p.isi)}</p>
                </div>
            `;
        } else {
            div.className = "flex items-start justify-end gap-4 fade-in mt-4";
            div.innerHTML = `
                <div class="bg-brand-900 p-4 rounded-2xl rounded-tr-sm max-w-[80%] shadow-[0_4px_15px_-5px_rgba(6,22,46,0.3)] relative group text-left">
                    <h4 class="text-xs font-bold text-brand-300 mb-1 flex justify-end items-center gap-2">
                        <span class="text-brand-400 font-normal">${p.waktu}</span> Anda (CS Admin)
                    </h4>
                    <p class="text-white text-sm leading-relaxed whitespace-pre-wrap">${escapeHTML(p.isi)}</p>
                    <div class="absolute bottom-2 right-3">
                        <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
            `;
        }
        
        container.appendChild(div);
    }
    
    function escapeHTML(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // --- Toggle Detail Konsultasi ---
    function toggleDetail() {
        const content = document.getElementById('detail-content');
        const icon = document.getElementById('detail-icon');
        const text = document.getElementById('detail-text');
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
            if(text) text.textContent = 'Tutup';
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
            if(text) text.textContent = 'Tampilkan';
        }
    }
</script>
@endpush
@endsection
