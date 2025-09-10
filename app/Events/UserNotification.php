<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

       
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
class UserNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public string $message;
    /**
     * Create a new event instance.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn() 
    {
        return new Channel('notifications'); 
    }

    public function broadcastAs() 
    {
        return 'create';
    }

    public function broadcastWith()
    {
        return [
            'message' => "[{ $this->message}] New Post Received with title Haseeb."
        ];
    }
}
