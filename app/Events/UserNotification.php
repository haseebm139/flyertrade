<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $message) {}

    public function broadcastOn(): Channel
    {
        return new Channel('notifications');
    }

    public function broadcastAs(): string
    {
        return 'create';
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message];
    }
}