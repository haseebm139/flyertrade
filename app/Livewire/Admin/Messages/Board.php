<?php

namespace App\Livewire\Admin\Messages;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Kreait\Firebase\Factory;
use Google\Cloud\Core\Timestamp;

class Board extends Component
{
    public string $search = '';
    public string $filter = 'all';
    public string $audience = 'service-users';
    public ?string $activeConversationId = null;

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function setAudience(string $audience): void
    {
        $this->audience = $audience;
    }

    public function selectConversation(string $conversationId): void
    {
        $this->activeConversationId = $conversationId;
    }

    public function render()
    {
        return view('livewire.admin.messages.board', [
            'conversations' => $this->loadConversations(),
        ]);
    }

    private function loadConversations(): array
    {
        $database = $this->firestoreDatabase();
        if (!$database) {
            return [];
        }

        try {
            $documents = $database->collection('support_chat')
                ->orderBy('lastMessageTime', 'DESC')
                ->limit(100)
                ->documents();
        } catch (\Throwable $e) {
            Log::error('Failed to load support_chat conversations: ' . $e->getMessage());
            return [];
        }

        $search = trim(mb_strtolower($this->search));
        $rows = [];

        foreach ($documents as $doc) {
            if (!$doc->exists()) {
                continue;
            }

            $data = $doc->data();
            $userName = (string) ($data['userName'] ?? 'Unknown');
            $userId = (string) ($data['userId'] ?? '');
            $userType = (string) ($data['userType'] ?? '');

            if ($this->audience === 'service-users' && $userType !== 'customer') {
                continue;
            }
            if ($this->audience === 'service-provider' && $userType !== 'provider') {
                continue;
            }

            if ($search !== '') {
                $nameMatch = mb_strpos(mb_strtolower($userName), $search) !== false;
                $idMatch = mb_strpos(mb_strtolower($userId), $search) !== false;
                if (!$nameMatch && !$idMatch) {
                    continue;
                }
            }

            $lastMessageTime = $this->normalizeTimestamp($data['lastMessageTime'] ?? null);
            $unreadCount = 0;
            if (!empty($data['unreadCount']) && is_array($data['unreadCount'])) {
                $unreadCount = (int) ($data['unreadCount']['support'] ?? 0);
            }

            if ($this->filter === 'unread' && $unreadCount === 0) {
                continue;
            }

            $rows[] = [
                'id' => $doc->id(),
                'userName' => $userName,
                'userId' => $userId,
                'userType' => $userType,
                'userImage' => (string) ($data['userImage'] ?? 'assets/images/avatar/default.png'),
                'lastMessage' => (string) ($data['lastMessage'] ?? ''),
                'lastMessageTime' => $lastMessageTime?->diffForHumans(),
                'unreadCount' => $unreadCount,
            ];
        }

        return $rows;
    }

    private function firestoreDatabase()
    {
        $serviceAccountPath = env('FIREBASE_CREDENTIALS', storage_path('firebase/firebase_credentials.json'));
        if (!file_exists($serviceAccountPath)) {
            Log::warning('Firebase credentials file not found at: ' . $serviceAccountPath);
            return null;
        }

        try {
            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            return $factory->createFirestore()->database();
        } catch (\Throwable $e) {
            Log::error('Firestore init failed: ' . $e->getMessage());
            return null;
        }
    }

    private function normalizeTimestamp($value): ?Carbon
    {
        if ($value instanceof Timestamp) {
            return Carbon::instance($value->get());
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_string($value) && $value !== '') {
            return Carbon::parse($value);
        }

        return null;
    }
}
