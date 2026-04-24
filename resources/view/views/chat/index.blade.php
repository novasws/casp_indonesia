@extends('layouts.app')

@section('title', 'Konsultasi – ' . $konsultasi->konsultan->nama_lengkap . ' – CASP Indonesia')

@push('styles')
<style>
html, body { height: 100%; margin: 0; }
.chat-wrap{min-height:100vh;background:var(--gray-50);display:flex;flex-direction:column}
.chat-header{background:var(--blue-900);color:white;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.chat-consultant{display:flex;align-items:center;gap:12px}
.chat-avatar{width:42px;height:42px;border-radius:50%;background:var(--blue-600);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0}
.chat-name{font-weight:600;font-size:.95rem}
.chat-status{font-size:.75rem;color:var(--blue-300);display:flex;align-items:center;gap:4px}
.chat-status::before{content:'●';color:#4ADE80;font-size:.55rem}
.chat-timer{background:rgba(220,38,38,.9);padding:6px 14px;border-radius:100px;font-size:.85rem;font-weight:700;font-variant-numeric:tabular-nums}
.chat-actions{display:flex;align-items:center;gap:10px}
.chat-btn{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:white;font-size:.78rem;font-weight:500;padding:7px 14px;border-radius:6px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .15s;text-decoration:none}
.chat-btn:hover{background:rgba(255,255,255,.22)}
.chat-progress-bar{height:4px;background:rgba(0,0,0,.1);flex-shrink:0}
.chat-progress-fill{height:100%;background:var(--blue-400);transition:width 1s linear}
.chat-messages{flex:1;padding:24px;overflow-y:auto;display:flex;flex-direction:column;gap:16px}
.msg-date{text-align:center;font-size:.72rem;color:var(--gray-400);font-style:italic;margin:4px 0}
.msg-bubble{max-width:68%;display:flex;flex-direction:column;gap:4px}
.msg-bubble.consultant{align-self:flex-start}
.msg-bubble.client{align-self:flex-end}
.bubble-content{padding:12px 16px;border-radius:16px;font-size:.88rem;line-height:1.55}
.msg-bubble.consultant .bubble-content{background:white;border:1px solid var(--gray-200);border-bottom-left-radius:4px;color:var(--gray-900)}
.msg-bubble.client .bubble-content{background:var(--blue-600);color:white;border-bottom-right-radius:4px}
.bubble-time{font-size:.7rem;color:var(--gray-400)}
.msg-bubble.client .bubble-time{text-align:right}
.chat-input-area{background:white;border-top:1px solid var(--gray-200);padding:16px 20px;display:flex;align-items:flex-end;gap:10px;flex-shrink:0}
.chat-input{flex:1;padding:11px 16px;border:1px solid var(--gray-200);border-radius:10px;font-size:.88rem;font-family:'DM Sans',sans-serif;color:var(--gray-900);resize:none;min-height:44px;max-height:120px;transition:border-color .15s}
.chat-input:focus{outline:none;border-color:var(--blue-400);box-shadow:0 0 0 3px rgba(59,130,246,.08)}
.chat-send{width:44px;height:44px;border-radius:10px;background:var(--blue-600);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;transition:background .15s,transform .1s;flex-shrink:0}
.chat-send:hover{background:var(--blue-700)}
.chat-send:active{transform:scale(.94)}
.chat-locked{background:var(--blue-900);color:white;text-align:center;padding:32px 20px;flex-shrink:0}
.chat-locked h3{font-family:'DM Serif Display',serif;font-size:1.3rem;margin-bottom:8px}
.chat-locked p{color:#94A3B8;font-size:.85rem;margin-bottom:20px}
.btn-download{background:white;color:var(--blue-900);font-weight:600;padding:11px 24px;border-radius:8px;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.9rem;transition:opacity .15s;text-decoration:none;display:inline-block}
.btn-download:hover{opacity:.9}
.typing-indicator{display:none;align-self:flex-start;max-width:68%}
.typing-indicator .bubble-content{background:white;border:1px solid var(--gray-200);border-bottom-left-radius:4px;display:flex;gap:4px;align-items:center;padding:14px 16px}
.dot{width:7px;height:7px;border-radius:50%;background:#94A3B8;animation:blink 1.4s infinite}
.dot:nth-child(2){animation-delay:.2s}
.dot:nth-child(3){animation-delay:.4s}
@keyframes blink{0%,80%,100%{opacity:0.3}40%{opacity:1}}
</style>
@endpush

@section('content')
<div class="chat-wrap">
    {{-- HEADER --}}
    <div class="chat-header">
        <div class="chat-consultant">
            <div class="chat-avatar">{{ $konsultasi->konsultan->inisial }}</div>
            <div>
                <div class="chat-name">{{ $konsultasi->konsultan->nama_lengkap }}</div>
                <div class="chat-status">Online · {{ $konsultasi->konsultan->spesialisasi }}</div>
            </div>
        </div>

        <div class="chat-timer" id="chat-timer">{{ gmdate('i:s', $sisaDetik) }}</div>

        <div class="chat-actions">
            <a href="{{ route('chat.transkrip', $konsultasi->id) }}"
               class="chat-btn" target="_blank">⬇ Transkrip</a>
            <a href="{{ route('landing') }}" class="chat-btn">✕ Keluar</a>
        </div>
    </div>

    {{-- PROGRESS BAR --}}
    <div class="chat-progress-bar">
        <div class="chat-progress-fill" id="chat-progress"
             style="width:{{ $durasi > 0 ? round($sisaDetik / $durasi * 100) : 0 }}%"></div>
    </div>

    {{-- MESSAGES --}}
    <div class="chat-messages" id="chat-messages">
        <div class="msg-date">
            Sesi konsultasi dimulai —
            {{ now()->translatedFormat('d F Y, H.i') }} WIB
        </div>

        {{-- Pesan pembuka konsultan --}}
        <div class="msg-bubble consultant fade-in">
            <div class="bubble-content">
                Halo, selamat datang di sesi konsultasi CASP Indonesia.
                Saya {{ $konsultasi->konsultan->nama_lengkap }}, konsultan
                {{ strtolower($konsultasi->konsultan->spesialisasi) }}.
                Silakan ceritakan permasalahan hukum yang ingin Anda diskusikan.
            </div>
            <div class="bubble-time">{{ $konsultasi->mulai_at?->format('H.i') ?? now()->format('H.i') }}</div>
        </div>

        {{-- Pesan-pesan sebelumnya (jika ada) --}}
        @foreach($pesan as $p)
        <div class="msg-bubble {{ $p->pengirim }} fade-in">
            <div class="bubble-content">{{ $p->isi }}</div>
            <div class="bubble-time">{{ $p->waktu }}</div>
        </div>
        @endforeach

        {{-- Typing indicator --}}
        <div class="typing-indicator" id="typing">
            <div class="bubble-content">
                <div class="dot"></div><div class="dot"></div><div class="dot"></div>
            </div>
        </div>
    </div>

    {{-- SESSION EXPIRED --}}
    @if($sisaDetik <= 0)
    <div class="chat-locked">
        <div style="font-size:2rem;margin-bottom:12px">🔒</div>
        <h3>Waktu Konsultasi Habis</h3>
        <p>Sesi chat telah berakhir. Anda dapat mengunduh transkrip percakapan ini.</p>
        <a href="{{ route('chat.transkrip', $konsultasi->id) }}" class="btn-download">
            ⬇ Unduh Transkrip
        </a>
        <div style="margin-top:16px">
            <a href="{{ route('onboarding.index') }}"
               style="background:rgba(255,255,255,.12);color:white;border:1px solid rgba(255,255,255,.2);padding:10px 20px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;text-decoration:none;display:inline-block">
                Mulai Sesi Baru →
            </a>
        </div>
    </div>
    @else
    {{-- INPUT AREA --}}
    <div class="chat-input-area" id="chat-input-area">
        <textarea class="chat-input"
                  id="chat-input"
                  placeholder="Ketik pesan Anda..."
                  rows="1"
                  onkeydown="handleEnter(event)"
                  oninput="autoResize(this)"></textarea>
        <button class="chat-send" onclick="sendMessage()">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                <path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const KONSULTASI_ID  = {{ $konsultasi->id }};
const SISA_DETIK     = {{ $sisaDetik }};
const TOTAL_DETIK    = {{ $durasi }};
const KIRIM_URL      = '{{ route('chat.kirim-pesan', $konsultasi->id) }}';
const CSRF           = document.querySelector('meta[name="csrf-token"]').content;

let chatTimerSec = SISA_DETIK;
let chatInterval;

// ===== TIMER =====
if (chatTimerSec > 0) {
    chatInterval = setInterval(() => {
        if (chatTimerSec <= 0) { clearInterval(chatInterval); lockChat(); return; }
        chatTimerSec--;
        document.getElementById('chat-timer').textContent   = fmtTime(chatTimerSec);
        document.getElementById('chat-progress').style.width = (chatTimerSec / TOTAL_DETIK * 100) + '%';
    }, 1000);
}

function fmtTime(s) {
    let m = Math.floor(s / 60), sec = s % 60;
    return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
}

function now() {
    let d = new Date();
    return d.getHours().toString().padStart(2, '0') + '.' + d.getMinutes().toString().padStart(2, '0');
}

// ===== LOCK =====
function lockChat() {
    const inputArea = document.getElementById('chat-input-area');
    if (inputArea) inputArea.style.display = 'none';

    const locked = document.createElement('div');
    locked.className = 'chat-locked';
    locked.innerHTML = `
        <div style="font-size:2rem;margin-bottom:12px">🔒</div>
        <h3>Waktu Konsultasi Habis</h3>
        <p>Sesi chat telah berakhir. Anda dapat mengunduh transkrip percakapan ini.</p>
        <a href="{{ route('chat.transkrip', $konsultasi->id) }}" class="btn-download">⬇ Unduh Transkrip</a>
        <div style="margin-top:16px">
            <a href="{{ route('onboarding.index') }}" style="background:rgba(255,255,255,.12);color:white;border:1px solid rgba(255,255,255,.2);padding:10px 20px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;text-decoration:none;display:inline-block">
                Mulai Sesi Baru →
            </a>
        </div>`;
    document.querySelector('.chat-wrap').appendChild(locked);
}

// ===== KIRIM PESAN =====
const simulatedReplies = [
    'Baik, saya mengerti situasi Anda. Berdasarkan hukum yang berlaku di Indonesia, ada beberapa hal yang perlu kita perhatikan bersama.',
    'Terima kasih atas informasinya. Saya akan membantu Anda menganalisis permasalahan hukum tersebut secara menyeluruh.',
    'Itu merupakan pertanyaan yang sangat relevan. Dari sudut pandang hukum, posisi Anda cukup kuat dalam hal ini.',
    'Ada beberapa langkah hukum yang bisa Anda tempuh. Pertama, kita perlu mengumpulkan bukti-bukti yang mendukung.',
    'Saya memahami kekhawatiran Anda. Secara hukum, situasi ini sebenarnya cukup jelas dan ada solusi yang bisa ditempuh.',
];
let replyIdx = 0;

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const text  = input.value.trim();
    if (!text) return;

    addBubble(text, 'client', now());
    input.value = '';
    input.style.height = '44px';
    showTyping();

    try {
        await fetch(KIRIM_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ isi: text }),
        });
    } catch {}

    // Simulasi balasan konsultan (di production, gunakan WebSocket/Echo)
    setTimeout(() => {
        hideTyping();
        addBubble(simulatedReplies[replyIdx % simulatedReplies.length], 'consultant', now());
        replyIdx++;
    }, 1200 + Math.random() * 800);
}

function addBubble(text, role, time) {
    const msgs = document.getElementById('chat-messages');
    const div  = document.createElement('div');
    div.className = 'msg-bubble ' + role + ' fade-in';
    div.innerHTML = `<div class="bubble-content">${text}</div><div class="bubble-time">${time}</div>`;
    msgs.insertBefore(div, document.getElementById('typing'));
    msgs.scrollTop = msgs.scrollHeight;
}

function showTyping() {
    const t = document.getElementById('typing');
    if (t) { t.style.display = 'flex'; document.getElementById('chat-messages').scrollTop = 9999; }
}
function hideTyping() {
    const t = document.getElementById('typing');
    if (t) t.style.display = 'none';
}

function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}
function autoResize(el) {
    el.style.height = '44px';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}
</script>
@endpush