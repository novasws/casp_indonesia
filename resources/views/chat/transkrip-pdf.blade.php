<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Konsultasi CASP - #{{ $konsultasi->id }}</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 0; background: #fff; line-height: 1.6; }
        .header { border-bottom: 2px solid #0A2342; padding-bottom: 15px; margin-bottom: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; color: #0A2342; }
        .header p { margin: 5px 0 0 0; font-size: 14px; color: #666; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 14px; }
        .info-table th { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; width: 30%; color: #555; }
        .info-table td { padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; }
        .chat-container { margin-top: 20px; }
        .chat-bubble { margin-bottom: 20px; padding: 15px; border-radius: 8px; font-size: 14px; page-break-inside: avoid; }
        .chat-klien { background-color: #f8fafc; border-left: 4px solid #3B82F6; }
        .chat-konsultan { background-color: #f0fdfa; border-left: 4px solid #0D9488; }
        .chat-meta { font-size: 12px; color: #64748b; margin-bottom: 5px; font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; display: flex; justify-content: space-between; }
        .chat-isi { margin: 0; white-space: pre-wrap; padding-top: 5px; }
        .footer { margin-top: 50px; font-size: 11px; text-align: center; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 15px; }
        
        /* Hilangkan elemen non-print saat dicetak */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }

        /* Tombol print manual di web view */
        .print-btn {
            display: block; width: 200px; margin: 20px auto; padding: 12px 20px; text-align: center;
            background: #0A2342; color: white; text-decoration: none; border-radius: 50px; font-weight: bold;
            cursor: pointer; border: none; font-size: 14px;
        }
        .print-btn:hover { background: #123364; }
    </style>
</head>
<body onload="window.print()">

    <button onclick="window.print()" class="print-btn no-print">🖨️ Cetak / Simpan ke PDF</button>

    <div class="header">
        <h1>TRANSKRIP KONSULTASI HUKUM</h1>
        <p>CASP Indonesia Legal Services</p>
    </div>

    <table class="info-table">
        <tr>
            <th>Nomor Registrasi</th>
            <td>CASP-{{ str_pad($konsultasi->id, 5, '0', STR_PAD_LEFT) }}-{{ date('Y') }}</td>
        </tr>
        <tr>
            <th>Tanggal Konsultasi</th>
            <td>{{ $konsultasi->created_at->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Nama Klien</th>
            <td>{{ $konsultasi->klien_nama }}</td>
        </tr>
        <tr>
            <th>Konsultan Bertugas</th>
            <td>{{ $konsultasi->konsultan->nama }}</td>
        </tr>
        <tr>
            <th>Durasi Paket</th>
            <td>Sesi {{ $konsultasi->paket }} Jam</td>
        </tr>
    </table>

    <div style="font-size: 14px; font-weight: bold; color: #0A2342; margin-bottom: 15px;">Riwayat Percakapan:</div>

    <div class="chat-container">
        @forelse($konsultasi->pesans as $p)
            @php 
                $isKlien = $p->pengirim === 'klien';
            @endphp
            <div class="chat-bubble {{ $isKlien ? 'chat-klien' : 'chat-konsultan' }}">
                <div class="chat-meta">
                    <span>👤 {{ $isKlien ? $konsultasi->klien_nama : $konsultasi->konsultan->nama }}</span>
                    <span>⌚ {{ $p->created_at->format('H:i:s') }}</span>
                </div>
                <p class="chat-isi">{{ $p->isi }}</p>
            </div>
        @empty
            <div style="text-align: center; color: #999; font-style: italic; padding: 30px;">
                TIdak ada riwayat percakapan dalam sesi ini.
            </div>
        @endforelse
    </div>

    <div class="footer">
        <p>Dokumen ini adalah transkrip elektronik sistem otomatis CASP Indonesia dan sah tanpa tanda tangan.</p>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i:s') }}</p>
    </div>

</body>
</html>
