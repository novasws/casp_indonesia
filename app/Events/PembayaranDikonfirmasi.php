<?php

namespace App\Events;

use App\Models\Pembayaran;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PembayaranDikonfirmasi
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Pembayaran $pembayaran;

    /**
     * Create a new event instance.
     */
    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }
}