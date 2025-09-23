<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConversationsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $conversations = Conversation::query()
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->with(['participants.user:id,name'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return response()->json($conversations);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'participant_id' => ['required','integer','exists:users,id','different:auth'],
            'type' => ['nullable', Rule::in(['direct','admin'])],
        ]);

        $participantId = (int)$data['participant_id'];
        $type = $data['type'] ?? 'direct';

        // Find existing direct conversation
        $existing = Conversation::query()
            ->where('type', $type)
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->whereHas('participants', fn($q) => $q->where('user_id', $participantId))
            ->first();
        if ($existing) {
            return response()->json(['id' => $existing->id]);
        }

        return DB::transaction(function () use ($user, $participantId, $type) {
            $conv = Conversation::create([
                'created_by_id' => $user->id,
                'type' => $type,
            ]);
            ConversationParticipant::create([
                'conversation_id' => $conv->id,
                'user_id' => $user->id,
                'role' => $user->user_type ?? 'customer',
                'joined_at' => now(),
            ]);
            $other = User::findOrFail($participantId);
            ConversationParticipant::create([
                'conversation_id' => $conv->id,
                'user_id' => $other->id,
                'role' => $other->user_type ?? 'provider',
                'joined_at' => now(),
            ]);
            return response()->json(['id' => $conv->id], 201);
        });
    }
}

