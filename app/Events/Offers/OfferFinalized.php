<?php

namespace App\Events\Offers;

use App\Models\Offer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OfferFinalized implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Offer $offer)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('private-conversation.' . $this->offer->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'offer.finalized';
    }

    public function broadcastWith(): array
    {
        return [
            'offer_id' => $this->offer->id,
            'status' => $this->offer->status,
            'finalized_at' => $this->offer->finalized_at?->toISOString(),
        ];
    }
}

