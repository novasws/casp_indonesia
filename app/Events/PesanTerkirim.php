<?php

namespace App\Events;

use App\Models\Pesan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanTerkirim implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Pesan $pesan;

    /**
     * Create a new event instance.
     */
    public function __construct(Pesan $pesan)
    {
        $this->pesan = $pesan;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('konsultasi.' . $this->pesan->konsultasi_id),
        ];
    }

    /**
     * Data yang dikirim ke channel broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id'             => $this->pesan->id,
            'konsultasi_id'  => $this->pesan->konsultasi_id,
            'pengirim'       => $this->pesan->pengirim, // 'klien' | 'konsultan'
            'isi'            => $this->pesan->isi,
            'dikirim_at'     => $this->pesan->created_at->format('H.i'),
        ];
    }
}