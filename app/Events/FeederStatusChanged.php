<?php

namespace App\Events;

use App\Models\Feeder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeederStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Feeder $feeder)
    {
        //
    }

    /**
     * Broadcast on a public channel — feeder status is not sensitive,
     * any consumer on this feeder should receive the update.
     */
    public function broadcastOn(): Channel
    {
        return new Channel("feeder.{$this->feeder->id}");
    }

    public function broadcastAs(): string
    {
        return 'FeederStatusChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'feeder_id'        => $this->feeder->id,
            'feeder_name'      => $this->feeder->feeder_name,
            'substation_code'  => $this->feeder->substation_code,
            'status'           => $this->feeder->status,
        ];
    }
}
