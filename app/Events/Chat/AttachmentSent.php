<?php

namespace App\Events\Chat;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class AttachmentSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('private-conversation.' . $this->message->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'chat.attachment.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'attachments' => $this->message->attachments()->get(['id','url','mime','size','width','height','duration_ms'])->toArray(),
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}

