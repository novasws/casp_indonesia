<?php

namespace App\Events;

use App\Models\Keluhan;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KeluhanDikirim
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $keluhan;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Keluhan $keluhan
     * @return void
     */
    public function __construct(Keluhan $keluhan)
    {
        $this->keluhan = $keluhan;
    }
}
