<?php

namespace App\Events\Offers;

use App\Models\Offer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OfferCreated implements ShouldBroadcastNow
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
        return 'offer.created';
    }

    public function broadcastWith(): array
    {
        return [
            'offer_id' => $this->offer->id,
            'status' => $this->offer->status,
            'service_type' => $this->offer->service_type,
            'time_from' => $this->offer->time_from?->toISOString(),
            'time_to' => $this->offer->time_to?->toISOString(),
            'description' => $this->offer->description,
        ];
    }
}

