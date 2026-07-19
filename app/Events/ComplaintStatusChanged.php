<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Ticket $ticket)
    {
        //
    }

    /**
     * Broadcast on a private channel — only the ticket-owning consumer
     * is authorized to listen (enforced in routes/channels.php).
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("user.{$this->ticket->user_id}.complaints");
    }

    public function broadcastAs(): string
    {
        return 'ComplaintStatusChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'complaint_id' => $this->ticket->id,
            'user_id'      => $this->ticket->user_id,
            'status'       => $this->ticket->status,
        ];
    }
}
