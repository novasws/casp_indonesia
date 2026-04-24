<?php

namespace App\Events;

use App\Models\Konsultasi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KonsultasiDimulai implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Konsultasi $konsultasi;

    /**
     * Create a new event instance.
     */
    public function __construct(Konsultasi $konsultasi)
    {
        $this->konsultasi = $konsultasi;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('konsultasi.' . $this->konsultasi->id),
        ];
    }

    /**
     * Data yang dikirim ke channel broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'konsultasi_id' => $this->konsultasi->id,
            'klien_nama'    => $this->konsultasi->klien_nama,
            'konsultan_id'  => $this->konsultasi->konsultan_id,
            'paket'         => $this->konsultasi->paket,
            'mulai_at'      => $this->konsultasi->mulai_at,
        ];
    }
}