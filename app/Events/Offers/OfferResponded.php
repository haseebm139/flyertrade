<?php

namespace App\Events\Offers;

use App\Models\Offer;
use App\Models\OfferRevision;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OfferResponded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Offer $offer, public ?OfferRevision $revision = null)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('private-conversation.' . $this->offer->conversation_id);
    }

    public function broadcastAs(): string
    {
        return match ($this->offer->status) {
            'countered' => 'offer.countered',
            'bargained' => 'offer.bargained',
            'accepted' => 'offer.accepted',
            'declined' => 'offer.declined',
            default => 'offer.updated',
        };
    }

    public function broadcastWith(): array
    {
        return [
            'offer_id' => $this->offer->id,
            'status' => $this->offer->status,
            'revision' => $this->revision ? [
                'by_user_id' => $this->revision->by_user_id,
                'cost_items' => $this->revision->cost_items,
                'materials' => $this->revision->materials,
                'flat_fee' => (string)$this->revision->flat_fee,
                'currency' => $this->revision->currency,
                'subtotal' => (string)$this->revision->subtotal,
                'tax' => (string)$this->revision->tax,
                'total' => (string)$this->revision->total,
                'notes' => $this->revision->notes,
            ] : null,
        ];
    }
}

