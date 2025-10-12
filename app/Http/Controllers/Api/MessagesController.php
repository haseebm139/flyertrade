<?php

namespace App\Http\Controllers\Api;

use App\Events\Chat\AttachmentSent;
use App\Events\Chat\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function index(Request $request, int $conversationId)
    {
        $user = $request->user();
        $conversation = Conversation::findOrFail($conversationId);
        abort_unless($conversation->participants()->where('user_id', $user->id)->exists(), 403);

        $messages = Message::where('conversation_id', $conversationId)
            ->with('attachments')
            ->orderByDesc('id')
            ->paginate(30);

        return response()->json($messages);
    }

    public function store(Request $request, int $conversationId)
    {
        $user = $request->user();
        $conversation = Conversation::findOrFail($conversationId);
        abort_unless($conversation->participants()->where('user_id', $user->id)->exists(), 403);

        $data = $request->validate([
            'body' => ['nullable','string'],
            'attachments' => ['nullable','array'],
            'attachments.*.url' => ['required_with:attachments','string','max:2048'],
            'attachments.*.mime' => ['nullable','string','max:191'],
            'attachments.*.size' => ['nullable','integer'],
            'attachments.*.width' => ['nullable','integer'],
            'attachments.*.height' => ['nullable','integer'],
            'attachments.*.duration_ms' => ['nullable','integer'],
        ]);

        return DB::transaction(function () use ($conversation, $user, $data) {
            $kind = !empty($data['attachments']) ? 'attachment' : 'text';
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'kind' => $kind,
                'body' => $data['body'] ?? null,
                'meta' => null,
            ]);

            if (!empty($data['attachments'])) {
                foreach ($data['attachments'] as $att) {
                    Attachment::create([
                        'message_id' => $message->id,
                        'url' => $att['url'],
                        'mime' => $att['mime'] ?? null,
                        'size' => $att['size'] ?? null,
                        'width' => $att['width'] ?? null,
                        'height' => $att['height'] ?? null,
                        'duration_ms' => $att['duration_ms'] ?? null,
                        'meta' => null,
                    ]);
                }
                event(new AttachmentSent($message));
            } else {
                event(new MessageSent($message));
            }

            $conversation->update(['last_message_at' => now()]);

            return response()->json(['id' => $message->id], 201);
        });
    }
}

