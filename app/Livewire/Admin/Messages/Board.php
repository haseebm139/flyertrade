<?php

namespace App\Livewire\Admin\Messages;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Google\Cloud\Core\Timestamp;
use GuzzleHttp\Client;

class Board extends Component
{
    use WithFileUploads;
    public string $search = '';
    public string $filter = 'all';
    public string $audience = 'service-users';
    public bool $showCompose = false;
    public string $composeType = 'email';
    public ?string $activeConversationId = null;
    public array $activeConversationMeta = [
        'userName' => 'Unknown',
        'userEmail' => '',
        'userImage' => 'assets/images/icons/five.svg',
    ];
    protected $queryString = [
        'filter' => ['except' => 'all'],
        'audience' => ['except' => 'service-users'],
    ];
    protected $updatesQueryString = ['filter', 'audience'];
    public array $cachedConversations = [];
    public array $allConversations = [];


    public array $messages = [];
    public bool $loadingMessages = false;
    public bool $loadingMoreMessages = false;
    public bool $hasMoreMessages = false;
    public int $messagesLimit = 20;
    public array $messagesCache = [];
    public array $messagesHasMoreCache = [];
    public ?string $messagesConversationId = null;
    public int $newIncomingCount = 0;
    public array $pendingLocalMessages = [];
    public string $replyMessage = '';
    public ?string $replyMediaUrl = null;
    public ?string $replyMediaType = null;
    public $replyMediaFile = null;
    public string $composeMessageText = '';
    public bool $selectAll = false;
    public array $selectedConversationIds = [];
    public string $filterStatus = 'all';
    // Conversation
    public function sendReply(): void
    {
        $messageText = trim($this->replyMessage);
        if (
            !$this->activeConversationId
            || ($messageText === '' && !$this->replyMediaUrl && !$this->replyMediaFile)
        ) {
            return;
        }

        $database = $this->firestoreDatabase();

        try {
            if ($this->replyMediaFile) {
                $this->storePendingAttachment();
            }
            $senderName = auth()->user()?->name ?? 'Support Team';
            $senderId = auth()->id();
            $senderImage = 'assets/images/avatar/default.png';
            $receiverId = $this->activeConversationMeta['userId'] ?? null;
            $receiverName = $this->activeConversationMeta['userName'] ?? 'Support Team';
            $receiverImage = $this->activeConversationMeta['userImage'] ?? null;
            $hasAttachment = !empty($this->replyMediaUrl);
            $messageType = $this->replyMediaType ?: ($hasAttachment ? 'media' : 'text');
            $mediaType = $this->replyMediaType ?: null;

            $clientMessageId = (string) Str::uuid();
            if ($database) {
                $payload = [
                    'message' => $messageText,
                    'messageType' => $messageType,
                    'client_message_id' => $clientMessageId,
                    'senderId' => $senderId,
                    'senderName' => $senderName,
                    'senderImage' => $senderImage,
                    'receiverId' => $receiverId,
                    'receiverName' => $receiverName,
                    'receiverImage' => $receiverImage,
                    'isRead' => false,
                    'senderType' => 'support',
                    'createdAt' => new Timestamp(new \DateTime()),
                    'updatedAt' => new Timestamp(new \DateTime()),
                    'seen' => false,
                ];
                if ($this->replyMediaUrl) {
                    $payload['mediaUrl'] = $this->replyMediaUrl;
                    $payload['mediaThumbnail'] = null;
                }
                if ($mediaType) {
                    $payload['mediaType'] = $mediaType;
                }

                $database
                    ->collection('support_chat')
                    ->document($this->activeConversationId)
                    ->collection('messages')
                    ->add($payload);

                $lastMessageText = $messageText !== ''
                    ? $messageText
                    : (($this->replyMediaType ?? 'media') . ' attachment');

                // update parent doc
                $database->collection('support_chat')
                    ->document($this->activeConversationId)
                    ->update([
                        ['path' => 'lastMessage', 'value' => $lastMessageText],
                        ['path' => 'lastMessageTime', 'value' => new Timestamp(new \DateTime())],
                        ['path' => 'lastMessageSenderId', 'value' => 'support'],
                        ['path' => 'unreadCount.support', 'value' => 0],
                    ]);
            } else {
                $this->sendReplyViaRest(
                    $this->activeConversationId,
                    $messageText,
                $messageType,
                    $senderId,
                    $senderName,
                    $senderImage,
                    $receiverId,
                    $receiverName,
                    $receiverImage,
                    $this->replyMediaUrl,
                $mediaType,
                $clientMessageId
                );
            }

            $createdAtTs = now()->timestamp;
            $sentMediaType = $messageType;
            $localMessage = [
                'id' => 'local-' . $clientMessageId,
                'clientMessageId' => $clientMessageId,
                'text' => $messageText,
                'sender' => 'support',
                'mediaUrl' => $this->replyMediaUrl,
                'messageType' => $messageType,
                'time' => now()->diffForHumans(),
                'createdAtTs' => $createdAtTs,
            ];
            $this->messages[] = $localMessage;
            $this->pendingLocalMessages[$this->activeConversationId][] = [
                'id' => $localMessage['id'],
                'clientMessageId' => $clientMessageId,
                'fingerprint' => $this->messageFingerprint(
                    $localMessage['text'] ?? '',
                    $localMessage['mediaUrl'] ?? null,
                    $localMessage['messageType'] ?? null
                ),
            ];
            $this->replyMessage = '';
            $this->replyMediaUrl = null;
            $this->replyMediaType = null;
            $this->replyMediaFile = null;
            $this->dispatch('clear-attachment-preview');
            $this->messagesCache[$this->activeConversationId] = $this->messages;
            $this->messagesConversationId = $this->activeConversationId;
            $this->newIncomingCount = 0;

            $lastMessageText = $messageText !== ''
                ? $messageText
                : (($sentMediaType ?? 'media') . ' attachment');
            $this->updateConversationPreview($this->activeConversationId, $lastMessageText, $createdAtTs);
            $this->dispatch('scroll-chat-bottom');
        } catch (\Throwable $e) {
            Log::error('Send reply failed: ' . $e->getMessage());
        }
    }

    private function storePendingAttachment(): void
    {
        $this->validate([
            'replyMediaFile' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,avi,mov,wmv,flv,webm|max:51200',
        ]);

        $mimeType = $this->replyMediaFile->getMimeType();
        $isImage = Str::startsWith($mimeType, 'image/');
        $folder = $isImage ? 'chat/images' : 'chat/videos';
        $directory = $folder . '/' . (auth()->id() ?? 'anonymous');
        $filename = Str::uuid() . '.' . $this->replyMediaFile->getClientOriginalExtension();

        $path = $this->replyMediaFile->storeAs($directory, $filename, 'public');
        if (!Storage::disk('public')->exists($path)) {
            throw new \RuntimeException('Failed to store file. Please check storage permissions.');
        }
        
        $this->replyMediaUrl = Storage::disk('public')->url($path);
        $this->replyMediaType = $isImage ? 'image' : 'video';
    }

    private function updateConversationPreview(string $conversationId, string $lastMessage, int $timestamp): void
    {
        $timeLabel = \Carbon\Carbon::createFromTimestamp($timestamp)->diffForHumans();
        $this->cachedConversations = $this->updateConversationCollection(
            $this->cachedConversations,
            $conversationId,
            $lastMessage,
            $timeLabel,
            $timestamp
        );
        $this->allConversations = $this->updateConversationCollection(
            $this->allConversations,
            $conversationId,
            $lastMessage,
            $timeLabel,
            $timestamp
        );
    }

    private function updateConversationCollection(
        array $conversations,
        string $conversationId,
        string $lastMessage,
        string $timeLabel,
        int $timestamp
    ): array {
        if (empty($conversations)) {
            return $conversations;
        }

        $index = null;
        foreach ($conversations as $i => $conversation) {
            if ((string) ($conversation['id'] ?? '') === $conversationId) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return $conversations;
        }

        $conversation = $conversations[$index];
        $conversation['lastMessage'] = $lastMessage;
        $conversation['lastMessageTime'] = $timeLabel;
        $conversation['lastMessageAt'] = $timestamp;
        $conversation['unreadCount'] = 0;

        unset($conversations[$index]);
        array_unshift($conversations, $conversation);

        return array_values($conversations);
    }

    private function sendReplyViaRest(
        string $conversationId,
        string $message,
        string $messageType,
        $senderId,
        string $senderName,
        string $senderImage,
        $receiverId,
        string $receiverName,
        ?string $receiverImage,
        ?string $mediaUrl = null,
        ?string $mediaType = null,
        ?string $clientMessageId = null
    ): void
    {
        $serviceAccount = $this->loadServiceAccount();
        if (!$serviceAccount) {
            throw new \RuntimeException('Firebase credentials missing.');
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            throw new \RuntimeException('Firebase project_id missing.');
        }

        $token = $this->fetchAccessToken($serviceAccount);
        if (!$token) {
            throw new \RuntimeException('Unable to fetch Firestore access token.');
        }

        $client = $this->makeHttpClient();
        $now = now()->toRfc3339String();

        $messageFields = [
            'message' => ['stringValue' => $message],
            'messageType' => ['stringValue' => $messageType],
            'senderName' => ['stringValue' => $senderName],
            'senderImage' => ['stringValue' => $senderImage],
            'receiverName' => ['stringValue' => $receiverName],
            'senderType' => ['stringValue' => 'support'],
            'createdAt' => ['timestampValue' => $now],
            'updatedAt' => ['timestampValue' => $now],
            'isRead' => ['booleanValue' => false],
            'seen' => ['booleanValue' => false],
        ];
        if ($clientMessageId) {
            $messageFields['client_message_id'] = ['stringValue' => $clientMessageId];
        }
        if ($senderId !== null) {
            $messageFields['senderId'] = ['integerValue' => (string) $senderId];
        }
        if ($receiverId !== null) {
            $messageFields['receiverId'] = ['integerValue' => (string) $receiverId];
        }
        if ($receiverImage !== null) {
            $messageFields['receiverImage'] = ['stringValue' => $receiverImage];
        }
        if ($mediaUrl) {
            $messageFields['mediaUrl'] = ['stringValue' => $mediaUrl];
            $messageFields['mediaThumbnail'] = ['nullValue' => null];
        }
        if ($mediaType) {
            $messageFields['mediaType'] = ['stringValue' => $mediaType];
        }

        $client->post(
            "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}/messages",
            [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'fields' => $messageFields,
                ],
            ]
        );

        $lastMessageText = $message !== '' ? $message : (($mediaType ?? 'media') . ' attachment');
        $client->patch(
            "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}?updateMask.fieldPaths=lastMessage&updateMask.fieldPaths=lastMessageTime&updateMask.fieldPaths=lastMessageSenderId&updateMask.fieldPaths=unreadCount.support",
            [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'fields' => [
                        'lastMessage' => ['stringValue' => $lastMessageText],
                        'lastMessageTime' => ['timestampValue' => $now],
                        'lastMessageSenderId' => ['stringValue' => 'support'],
                        'unreadCount' => [
                            'mapValue' => [
                                'fields' => [
                                    'support' => ['integerValue' => '0'],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
    private function loadMessagesFromRest(string $conversationId, int $limit, bool $reset = true): void
    {
        if ($reset) {
            $this->loadingMessages = true;
        } else {
            $this->loadingMoreMessages = true;
        }

        $serviceAccount = $this->loadServiceAccount();
        if (!$serviceAccount) {
            $this->loadingMessages = false;
            $this->loadingMoreMessages = false;
            return;
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            $this->loadingMessages = false;
            $this->loadingMoreMessages = false;
            return;
        }

        $token = $this->fetchAccessToken($serviceAccount);
        if (!$token) {
            $this->loadingMessages = false;
            $this->loadingMoreMessages = false;
            return;
        }

        $client = $this->makeHttpClient();
        $fetchLimit = $limit + 1;
        $response = $client->get(
            "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}/messages",
            [
                'query' => ['pageSize' => $fetchLimit, 'orderBy' => 'createdAt desc'],
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]
        );

        $payload = json_decode((string) $response->getBody(), true);
        $documents = $payload['documents'] ?? [];
            
        $messages = [];
        foreach ($documents as $doc) {
            $fields = $doc['fields'] ?? [];
            if (!$fields) {
                continue;
            }

            try {
                $createdAt = $this->getFirestoreFieldValue($fields, 'createdAt');
                $messageText = (string) $this->getFirestoreFieldValue($fields, 'message', $this->getFirestoreFieldValue($fields, 'text', ''));
                $mediaUrl = $this->getFirestoreFieldValue($fields, 'mediaUrl', $this->getFirestoreFieldValue($fields, 'mediaURL', null));
                $messageType = (string) $this->getFirestoreFieldValue(
                    $fields,
                    'messageType',
                    $this->getFirestoreFieldValue($fields, 'mediaType', ($mediaUrl ? 'media' : 'text'))
                );
                $clientMessageId = (string) $this->getFirestoreFieldValue($fields, 'client_message_id', '');
                $senderName = (string) $this->getFirestoreFieldValue($fields, 'senderName', '');
                $senderType = (string) $this->getFirestoreFieldValue($fields, 'senderType', $senderName === 'Support Team' ? 'support' : 'user');

                $createdAtTs = $this->normalizeTimestamp($createdAt)?->timestamp ?? 0;
                $message = [
                    'id' => basename((string) ($doc['name'] ?? '')),
                    'clientMessageId' => $clientMessageId ?: null,
                    'text' => $messageText,
                    'sender' => $senderType,
                    'mediaUrl' => $mediaUrl,
                    'messageType' => $messageType,
                    'time' => $this->normalizeTimestamp($createdAt)?->diffForHumans(),
                    'createdAtTs' => $createdAtTs,
                ];
                $messages[] = $message;
            } catch (\Throwable $e) {
                Log::error('Load messages REST fallback failed: ' . $e->getMessage());
            }
        }

        $hasMore = count($messages) > $limit;
        if ($hasMore) {
            $messages = array_slice($messages, 0, $limit);
        }
        $this->messages = array_reverse($messages);
        $this->messagesLimit = $limit;
        $this->hasMoreMessages = $hasMore;
        $this->loadingMessages = false;
        $this->loadingMoreMessages = false;
        $this->messagesCache[$conversationId] = $this->messages;
        $this->messagesHasMoreCache[$conversationId] = $this->hasMoreMessages;
        $this->messagesConversationId = $conversationId;
    }

    private function loadMessages(string $conversationId, ?int $limit = null, bool $reset = true): void
    {
        $limit = $limit ?? $this->messagesLimit;
        if ($reset) {
            $this->loadingMessages = true;
        } else {
            $this->loadingMoreMessages = true;
        }
        
        $database = $this->firestoreDatabase();
         
        if ($database) {
            try {
                $fetchLimit = $limit + 1;
                $documents = $database
                    ->collection('support_chat')
                    ->document($conversationId)
                    ->collection('messages')
                    ->orderBy('createdAt', 'DESC')
                    ->limit($fetchLimit)
                    ->documents();

                $messages = [];

                foreach ($documents as $doc) {
                    if (!$doc->exists()) continue;

                    $data = $doc->data();

                $messageText = $data['message'] ?? $data['text'] ?? '';
                $messageType = $data['messageType']
                    ?? ($data['mediaType'] ?? null)
                    ?? ($data['mediaUrl'] ?? null ? 'media' : 'text');
                $mediaUrl = $data['mediaUrl'] ?? $data['mediaURL'] ?? null;
                $clientMessageId = $data['client_message_id'] ?? null;
                $senderType = $data['senderType']
                    ?? (($data['senderName'] ?? '') === 'Support Team' ? 'support' : 'user');

                $createdAtTs = $this->normalizeTimestamp($data['createdAt'] ?? null)?->timestamp ?? 0;
                $messages[] = [
                    'id' => $doc->id(),
                    'clientMessageId' => $clientMessageId ?: null,
                    'text' => $messageText,
                    'sender' => $senderType,
                    'mediaUrl' => $mediaUrl,
                    'messageType' => $messageType,
                    'time' => $this->normalizeTimestamp($data['createdAt'])?->diffForHumans(),
                    'createdAtTs' => $createdAtTs,
                ];
                }

                $hasMore = count($messages) > $limit;
                if ($hasMore) {
                    $messages = array_slice($messages, 0, $limit);
                }
                $this->messages = array_reverse($messages);
                $this->messagesLimit = $limit;
                $this->hasMoreMessages = $hasMore;
                $this->loadingMessages = false;
                $this->loadingMoreMessages = false;
                $this->messagesCache[$conversationId] = $this->messages;
                $this->messagesHasMoreCache[$conversationId] = $this->hasMoreMessages;
                $this->messagesConversationId = $conversationId;
                return; // success, no need REST
            } catch (\Throwable $e) {
                Log::warning('Load messages gRPC failed, using REST fallback: ' . $e->getMessage());
            }
        } else {
            Log::warning('Firestore gRPC missing, using REST fallback for messages.');
        }

        // Fallback to REST
        $this->loadMessagesFromRest($conversationId, $limit, $reset);
    }    

    private function fetchLatestMessages(string $conversationId, int $limit): array
    {
        $database = $this->firestoreDatabase();
        $messages = [];

        if ($database) {
            try {
                $documents = $database
                    ->collection('support_chat')
                    ->document($conversationId)
                    ->collection('messages')
                    ->orderBy('createdAt', 'DESC')
                    ->limit($limit)
                    ->documents();

                foreach ($documents as $doc) {
                    if (!$doc->exists()) {
                        continue;
                    }

                    $data = $doc->data();
                    $messageText = $data['message'] ?? $data['text'] ?? '';
                    $messageType = $data['messageType']
                        ?? ($data['mediaType'] ?? null)
                        ?? ($data['mediaUrl'] ?? null ? 'media' : 'text');
                    $mediaUrl = $data['mediaUrl'] ?? $data['mediaURL'] ?? null;
                    $clientMessageId = $data['client_message_id'] ?? null;
                    $senderType = $data['senderType']
                        ?? (($data['senderName'] ?? '') === 'Support Team' ? 'support' : 'user');
                    $createdAtTs = $this->normalizeTimestamp($data['createdAt'] ?? null)?->timestamp ?? 0;

                    $messages[] = [
                        'id' => $doc->id(),
                        'clientMessageId' => $clientMessageId ?: null,
                        'text' => $messageText,
                        'sender' => $senderType,
                        'mediaUrl' => $mediaUrl,
                        'messageType' => $messageType,
                        'time' => $this->normalizeTimestamp($data['createdAt'] ?? null)?->diffForHumans(),
                        'createdAtTs' => $createdAtTs,
                    ];
                }

                return array_reverse($messages);
            } catch (\Throwable $e) {
                Log::warning('Fetch latest messages gRPC failed, using REST fallback: ' . $e->getMessage());
            }
        }

        return $this->fetchLatestMessagesFromRest($conversationId, $limit);
    }

    private function fetchLatestMessagesFromRest(string $conversationId, int $limit): array
    {
        $serviceAccount = $this->loadServiceAccount();
        if (!$serviceAccount) {
            return [];
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            return [];
        }

        $token = $this->fetchAccessToken($serviceAccount);
        if (!$token) {
            return [];
        }

        try {
            $client = $this->makeHttpClient();
            $response = $client->get(
                "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}/messages",
                [
                    'query' => ['pageSize' => $limit, 'orderBy' => 'createdAt desc'],
                    'headers' => [
                        'Authorization' => "Bearer {$token}",
                    ],
                ]
            );

            $payload = json_decode((string) $response->getBody(), true);
            $documents = $payload['documents'] ?? [];
        } catch (\Throwable $e) {
            Log::warning('Fetch latest messages REST failed: ' . $e->getMessage());
            return [];
        }

        $messages = [];
        foreach ($documents as $doc) {
            $fields = $doc['fields'] ?? [];
            if (!$fields) {
                continue;
            }

            $createdAt = $this->getFirestoreFieldValue($fields, 'createdAt');
            $createdAtTs = $this->normalizeTimestamp($createdAt)?->timestamp ?? 0;
            $messageText = (string) $this->getFirestoreFieldValue(
                $fields,
                'message',
                $this->getFirestoreFieldValue($fields, 'text', '')
            );
            $mediaUrl = $this->getFirestoreFieldValue(
                $fields,
                'mediaUrl',
                $this->getFirestoreFieldValue($fields, 'mediaURL', null)
            );
            $messageType = (string) $this->getFirestoreFieldValue(
                $fields,
                'messageType',
                $this->getFirestoreFieldValue($fields, 'mediaType', ($mediaUrl ? 'media' : 'text'))
            );
            $clientMessageId = (string) $this->getFirestoreFieldValue($fields, 'client_message_id', '');
            $senderName = (string) $this->getFirestoreFieldValue($fields, 'senderName', '');
            $senderType = (string) $this->getFirestoreFieldValue(
                $fields,
                'senderType',
                $senderName === 'Support Team' ? 'support' : 'user'
            );

            $messages[] = [
                'id' => basename((string) ($doc['name'] ?? '')),
                'clientMessageId' => $clientMessageId ?: null,
                'text' => $messageText,
                'sender' => $senderType,
                'mediaUrl' => $mediaUrl,
                'messageType' => $messageType,
                'time' => $this->normalizeTimestamp($createdAt)?->diffForHumans(),
                'createdAtTs' => $createdAtTs,
            ];
        }

        return array_reverse($messages);
    }
    public function selectConversation(string $conversationId): void
    {
        if ($this->activeConversationId === $conversationId && !$this->showCompose) {
            return;
        }

        $this->showCompose = false;
        $this->activeConversationId = $conversationId;
        $this->activeConversationMeta = $this->resolveActiveConversationMeta($conversationId);
        $this->newIncomingCount = 0;

        $this->messagesLimit = 20;
        $this->hasMoreMessages = false;
        if (isset($this->messagesCache[$conversationId]) && !empty($this->messagesCache[$conversationId])) {
            $this->messages = $this->messagesCache[$conversationId];
            $this->messagesConversationId = $conversationId;
            $this->loadingMessages = false;
            $this->messagesLimit = max($this->messagesLimit, count($this->messages));
            $this->hasMoreMessages = $this->messagesHasMoreCache[$conversationId] ?? false;
            $this->dispatch('scroll-chat-bottom');
        } else {
            $this->messages = [];
            $this->messagesConversationId = null;
            $this->loadingMessages = true;
            $this->hasMoreMessages = false;
        }
    }

    public function initConversation(): void
    {
        if (!$this->activeConversationId) {
            return;
        }

        $this->newIncomingCount = 0;
        if ($this->messagesConversationId === $this->activeConversationId && !empty($this->messages)) {
            // Cached messages already shown; skip reload for faster switching.
            $this->dispatch('scroll-chat-bottom');
            return;
        }

        $this->markConversationRead($this->activeConversationId);
        $this->loadMessages($this->activeConversationId, $this->messagesLimit, true);
        $this->dispatch('scroll-chat-bottom');
    }

    public function loadMoreMessages(): void
    {
        if (!$this->activeConversationId || $this->loadingMessages || $this->loadingMoreMessages) {
            return;
        }

        $nextLimit = $this->messagesLimit + 20;
        $this->loadMessages($this->activeConversationId, $nextLimit, false);
        $this->dispatch('scroll-chat-top');
    }

    public function closeConversation(): void
    {
        $this->activeConversationId = null;
        $this->messages = [];
        $this->newIncomingCount = 0;
    }

    public function openCompose(string $type = 'email'): void
    {
        $this->composeType = $type === 'message' ? 'message' : 'email';
        $this->showCompose = true;
        if ($this->composeType === 'message' && empty($this->selectedConversationIds) && $this->activeConversationId) {
            $this->selectedConversationIds = [$this->activeConversationId];
        }
    }

    public function closeCompose(): void
    {
        $this->showCompose = false;
    }

    public function getPanelStateProperty(): string
    {
        if ($this->showCompose) {
            return $this->composeType === 'message' ? 'compose_message' : 'compose_email';
        }

        if ($this->hasActiveConversation) {
            return 'chat';
        }

        return 'empty';
    }

    public function getSelectedRecipientsProperty(): array
    {
        $source = !empty($this->cachedConversations) ? $this->cachedConversations : $this->allConversations;
        if (empty($source) || empty($this->selectedConversationIds)) {
            return [];
        }

        $indexed = [];
        foreach ($source as $conversation) {
            $id = (string) ($conversation['id'] ?? '');
            if ($id !== '') {
                $indexed[$id] = $conversation;
            }
        }

        $recipients = [];
        foreach ($this->selectedConversationIds as $id) {
            $id = (string) $id;
            if (isset($indexed[$id])) {
                $recipients[] = $indexed[$id];
            }
        }

        return $recipients;
    }

    public function sendComposeMessage(): void
    {
        $messageText = trim($this->composeMessageText);
        if ($messageText === '') {
            return;
        }
        if (empty($this->selectedConversationIds) && $this->activeConversationId) {
            $this->selectedConversationIds = [$this->activeConversationId];
        }
        if (empty($this->selectedConversationIds)) {
            return;
        }

        $senderName = auth()->user()?->name ?? 'Support Team';
        $senderId = auth()->id();
        $senderImage = 'assets/images/avatar/default.png';
        $createdAtTs = now()->timestamp;

        $database = $this->firestoreDatabase();
        $conversationIds = array_values(array_unique($this->selectedConversationIds));
        foreach ($conversationIds as $conversationId) {
            $conversationId = (string) $conversationId;
            if ($conversationId === '') {
                continue;
            }

            $meta = $this->resolveConversationMeta($conversationId);
            $receiverId = $meta['userId'] ?? null;
            $receiverName = $meta['userName'] ?? 'Support Team';
            $receiverImage = $meta['userImage'] ?? null;
            $clientMessageId = (string) Str::uuid();

            if ($database) {
                $payload = [
                    'message' => $messageText,
                    'messageType' => 'text',
                    'client_message_id' => $clientMessageId,
                    'senderId' => $senderId,
                    'senderName' => $senderName,
                    'senderImage' => $senderImage,
                    'receiverId' => $receiverId,
                    'receiverName' => $receiverName,
                    'receiverImage' => $receiverImage,
                    'isRead' => false,
                    'senderType' => 'support',
                    'createdAt' => new Timestamp(new \DateTime()),
                    'updatedAt' => new Timestamp(new \DateTime()),
                    'seen' => false,
                ];

                $database
                    ->collection('support_chat')
                    ->document($conversationId)
                    ->collection('messages')
                    ->add($payload);

                $database->collection('support_chat')
                    ->document($conversationId)
                    ->update([
                        ['path' => 'lastMessage', 'value' => $messageText],
                        ['path' => 'lastMessageTime', 'value' => new Timestamp(new \DateTime())],
                        ['path' => 'lastMessageSenderId', 'value' => 'support'],
                        ['path' => 'unreadCount.support', 'value' => 0],
                    ]);
            } else {
                $this->sendReplyViaRest(
                    $conversationId,
                    $messageText,
                    'text',
                    $senderId,
                    $senderName,
                    $senderImage,
                    $receiverId,
                    $receiverName,
                    $receiverImage,
                    null,
                    null,
                    $clientMessageId
                );
            }

            $this->updateConversationPreview($conversationId, $messageText, $createdAtTs);
        }

        $this->composeMessageText = '';
        $this->showCompose = false;
        $this->selectAll = false;
        $this->selectedConversationIds = [];
    }

    private function resolveConversationMeta(string $id): array
    {
        $source = !empty($this->allConversations) ? $this->allConversations : $this->cachedConversations;
        foreach ($source as $conversation) {
            if ((string) ($conversation['id'] ?? '') === $id) {
                return [
                    'userName' => $conversation['userName'] ?? 'Unknown',
                    'userEmail' => $conversation['userId'] ?? '',
                    'userImage' => $conversation['userImage'] ?? 'assets/images/icons/five.svg',
                    'userId' => $conversation['userId'] ?? null,
                ];
            }
        }

        return [
            'userName' => 'Support',
            'userEmail' => '',
            'userImage' => 'assets/images/icons/five.svg',
            'userId' => null,
        ];
    }

    public function selectPreviousConversation(): void
    {
        $index = $this->getActiveConversationIndex();
        if ($index === null || $index <= 0) {
            return;
        }

        $prev = $this->cachedConversations[$index - 1] ?? null;
        if ($prev && !empty($prev['id'])) {
            $this->selectConversation((string) $prev['id']);
        }
    }

    public function selectNextConversation(): void
    {
        $index = $this->getActiveConversationIndex();
        if ($index === null) {
            return;
        }

        $next = $this->cachedConversations[$index + 1] ?? null;
        if ($next && !empty($next['id'])) {
            $this->selectConversation((string) $next['id']);
        }
    }

    private function getActiveConversationIndex(): ?int
    {
        if (!$this->activeConversationId) {
            return null;
        }

        foreach ($this->cachedConversations as $index => $conversation) {
            if ((string) ($conversation['id'] ?? '') === (string) $this->activeConversationId) {
                return $index;
            }
        }

        return null;
    }

    public function clearAttachment(): void
    {
        $this->replyMediaUrl = null;
        $this->replyMediaType = null;
        $this->replyMediaFile = null;
    }

    // Attachment is stored on send via storePendingAttachment().

    private function markConversationRead(string $conversationId): void
    {
        $database = $this->firestoreDatabase();

        try {
            if ($database) {
                $database->collection('support_chat')
                    ->document($conversationId)
                    ->update([
                        ['path' => 'unreadCount.support', 'value' => 0],
                    ]);
            } else {
                $this->markConversationReadViaRest($conversationId);
            }

            $this->updateUnreadCount($conversationId, 0);
        } catch (\Throwable $e) {
            Log::warning('Mark conversation read failed: ' . $e->getMessage());
        }
    }

    private function updateUnreadCount(string $conversationId, int $count): void
    {
        foreach ($this->cachedConversations as $index => $conversation) {
            if ((string) ($conversation['id'] ?? '') === $conversationId) {
                $this->cachedConversations[$index]['unreadCount'] = $count;
            }
        }
        foreach ($this->allConversations as $index => $conversation) {
            if ((string) ($conversation['id'] ?? '') === $conversationId) {
                $this->allConversations[$index]['unreadCount'] = $count;
            }
        }
    }

    private function markConversationReadViaRest(string $conversationId): void
    {
        $serviceAccount = $this->loadServiceAccount();
        if (!$serviceAccount) {
            return;
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            return;
        }

        $token = $this->fetchAccessToken($serviceAccount);
        if (!$token) {
            return;
        }

        $client = $this->makeHttpClient();
        $client->patch(
            "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}?updateMask.fieldPaths=unreadCount.support",
            [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'fields' => [
                        'unreadCount' => [
                            'mapValue' => [
                                'fields' => [
                                    'support' => ['integerValue' => '0'],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
    // Side Bar
    public function updatedSearch(): void
    {
        $this->activeConversationId = null;
        $this->refreshConversationsCache();
        $this->resetSelection();
    }
     
    public function getHasActiveConversationProperty(): bool
    {
        return !empty($this->activeConversationId);
    }

    public function refreshInbox(): void
    {
        $this->cachedConversations = [];
        $this->allConversations = [];
        $this->resetSelection();
    }
    public function render()
    {

        if (empty($this->cachedConversations)) {
            $this->refreshConversationsCache();
        }
        $visibleIds = array_values(array_map(
            static fn($conversation) => (string) ($conversation['id'] ?? ''),
            $this->cachedConversations
        ));
        if ($this->selectAll) {
            $this->selectedConversationIds = $visibleIds;
        } else {
            $this->selectedConversationIds = array_values(
                array_intersect($this->selectedConversationIds, $visibleIds)
            );
        }
        $this->activeConversationMeta = $this->resolveActiveConversationMeta($this->activeConversationId);
         
        return view('livewire.admin.messages.board', [
            'conversations' => $this->cachedConversations,
        ]);
         
    }
    public function switchTab(string $type, string $value)
    {
        if ($type === 'filter') {
            $this->filter = $value;
        }

        if ($type === 'audience') {
            $this->audience = $value;
        }

        if ($type === 'filter') {
            $this->audience = 'service-users';
        }

        $this->refreshConversationsCache();
        $this->resetSelection();
    }

    private function refreshConversationsCache(): void
    {
        $this->cachedConversations = $this->loadConversations();
    }

    public function toggleSelectAll(): void
    {
        $visibleIds = array_values(array_map(
            static fn($conversation) => (string) ($conversation['id'] ?? ''),
            $this->cachedConversations
        ));

        if (empty($visibleIds)) {
            $this->selectAll = false;
            $this->selectedConversationIds = [];
            return;
        }

        if ($this->selectAll) {
            $this->selectAll = false;
            $this->selectedConversationIds = [];
            return;
        }

        $this->selectAll = true;
        $this->selectedConversationIds = $visibleIds;
    }

    public function updatedSelectedConversationIds(): void
    {
        $visibleIds = array_values(array_map(
            static fn($conversation) => (string) ($conversation['id'] ?? ''),
            $this->cachedConversations
        ));
        $selectedVisible = array_values(array_intersect($this->selectedConversationIds, $visibleIds));
        $this->selectedConversationIds = $selectedVisible;
        $this->selectAll = !empty($visibleIds) && count($selectedVisible) === count($visibleIds);
    }

    private function resetSelection(): void
    {
        $this->selectAll = false;
        $this->selectedConversationIds = [];
    }
    private function loadConversations(): array
    {
        if (empty($this->allConversations)) {
            $database = $this->firestoreDatabase();

            if (!$database) {
                $this->allConversations = $this->loadConversationsFromRest();
            } else {
                $documents = null;
                $usedFallback = false;

                try {
                    $documents = $database->collection('support_chat')
                        ->orderBy('lastMessageTime', 'DESC')
                        ->limit(100)
                        ->documents();
                } catch (\Throwable $e) {
                    Log::warning('OrderBy lastMessageTime failed, falling back to unsorted fetch: ' . $e->getMessage());
                    $usedFallback = true;
                }

                if ($documents === null) {
                    try {
                        $documents = $database->collection('support_chat')
                            ->limit(100)
                            ->documents();
                    } catch (\Throwable $e) {
                        Log::error('Failed to load support_chat conversations: ' . $e->getMessage());
                        $this->allConversations = $this->loadConversationsFromRest();
                        $documents = null;
                    }
                }

                if ($documents !== null) {
                    $rows = [];
                    foreach ($documents as $doc) {
                        if (!$doc->exists()) {
                            continue;
                        }

                        $data = $doc->data();
                        $userName = (string) ($data['userName'] ?? 'Unknown');
                        $userId = (string) ($data['userId'] ?? '');
                        $userType = (string) ($data['userType'] ?? '');
                        $userImage = (string) ($data['userImage'] ?? '');
                        $userImage = $this->normalizeConversationImage($userImage, $doc->id(), $database);
                        $rawConversationType = (string) ($data['conversationType']
                            ?? $data['conversation_type']
                            ?? $data['messageType']
                            ?? $data['message_type']
                            ?? $data['channel']
                            ?? $data['type']
                            ?? '');
                        $conversationType = $this->normalizeConversationType($rawConversationType);

                        $lastMessageTime = $this->normalizeTimestamp($data['lastMessageTime'] ?? null);
                        $unreadCount = 0;
                        if (isset($data['unreadCount'])) {
                            if (is_array($data['unreadCount'])) {
                                $unreadCount = (int) ($data['unreadCount']['support'] ?? 0);
                            } else {
                                $unreadCount = (int) $data['unreadCount'];
                            }
                        }

                        $rows[] = [
                            'id' => $doc->id(),
                            'userName' => $userName,
                            'userId' => $userId,
                            'userType' => $userType,
                            'userImage' => $userImage,
                            'lastMessage' => (string) ($data['lastMessage'] ?? ''),
                            'lastMessageTime' => $lastMessageTime?->diffForHumans(),
                            'lastMessageAt' => $lastMessageTime?->timestamp ?? 0,
                            'unreadCount' => $unreadCount,
                            'conversationType' => $conversationType,
                        ];
                    }

                    if ($usedFallback) {
                        usort($rows, static function (array $left, array $right): int {
                            return ($right['lastMessageAt'] ?? 0) <=> ($left['lastMessageAt'] ?? 0);
                        });
                    }

                    $this->allConversations = $rows;
                }
            }
        }

        return $this->applyConversationFilters($this->allConversations);
    }

    private function applyConversationFilters(array $rows): array
    {
        $search = trim(mb_strtolower($this->search));
        $filtered = [];

        foreach ($rows as $row) {
            $userName = (string) ($row['userName'] ?? 'Unknown');
            $userId = (string) ($row['userId'] ?? '');
            $userType = (string) ($row['userType'] ?? '');
            $conversationType = (string) ($row['conversationType'] ?? 'chat');
            $unreadCount = (int) ($row['unreadCount'] ?? 0);

            $normalizedType = str_replace(['_', ' '], '-', mb_strtolower(trim($userType)));
            $isCustomer = $normalizedType !== '' && in_array($normalizedType, [
                'customer',
                'service-user',
                'service-users',
                'serviceuser',
                'serviceusers',
            ], true);
            $isProvider = $normalizedType !== '' && in_array($normalizedType, [
                'provider',
                'service-provider',
                'service-providers',
                'serviceprovider',
                'serviceproviders',
            ], true);

            if ($this->audience === 'service-users' && !$isCustomer) {
                continue;
            }
            if ($this->audience === 'service-provider' && !$isProvider) {
                continue;
            }

            if ($search !== '') {
                $nameMatch = mb_strpos(mb_strtolower($userName), $search) !== false;
                $idMatch = mb_strpos(mb_strtolower($userId), $search) !== false;
                if (!$nameMatch && !$idMatch) {
                    continue;
                }
            }

            if ($this->filter === 'unread' && $unreadCount === 0) {
                continue;
            }
            if ($this->filter === 'emails' && $conversationType !== 'email') {
                continue;
            }
            if ($this->filter === 'chats' && $conversationType !== 'chat') {
                continue;
            }

            $filtered[] = $row;
        }

        foreach ($filtered as $index => $row) {
            unset($filtered[$index]['lastMessageAt']);
        }

        return $filtered;
    }

    private function resolveActiveConversationMeta(?string $id): array
    {
        if (!$id) {
            return [
                'userName' => 'Support',
                'userEmail' => '',
                'userImage' => 'assets/images/icons/five.svg',
            ];
        }

        $source = !empty($this->allConversations) ? $this->allConversations : $this->cachedConversations;

        foreach ($source as $conversation) {
            if ((string) $conversation['id'] === $id) {
                return [
                    'userName' => $conversation['userName'] ?? 'Unknown',
                    'userEmail' => $conversation['userId'] ?? '',
                    'userImage' => $conversation['userImage'] ?? 'assets/images/icons/five.svg',
                    'userId' => $conversation['userId'] ?? null,
                ];
            }
        }

        return [
            'userName' => 'Support',
            'userEmail' => '',
            'userImage' => 'assets/images/icons/five.svg',
        ];
    }

    public function pollMessages(): void
    {
        if ($this->activeConversationId && !$this->loadingMessages && !$this->loadingMoreMessages) {
            $this->appendNewMessages($this->activeConversationId);
        }
    }

    private function appendNewMessages(string $conversationId): void
    {
        $snapshot = $this->fetchLatestMessages($conversationId, 5);
        if (empty($snapshot)) {
            return;
        }

        $existingIds = array_flip(array_map(
            static fn($message) => (string) ($message['id'] ?? ''),
            $this->messages
        ));

        $pending = $this->pendingLocalMessages[$conversationId] ?? [];
        $pendingMap = [];
        $pendingByClientId = [];
        foreach ($pending as $entry) {
            $pendingMap[$entry['fingerprint']][] = $entry['id'];
            if (!empty($entry['clientMessageId'])) {
                $pendingByClientId[$entry['clientMessageId']] = $entry['id'];
            }
        }

        $newMessages = [];
        $clearedLocalIds = [];
        foreach ($snapshot as $message) {
            $id = (string) ($message['id'] ?? '');
            if ($id === '' || isset($existingIds[$id])) {
                continue;
            }
            if (($message['sender'] ?? '') === 'support') {
                $clientMessageId = (string) ($message['clientMessageId'] ?? '');
                if ($clientMessageId !== '' && isset($pendingByClientId[$clientMessageId])) {
                    $localId = $pendingByClientId[$clientMessageId];
                    $this->messages = array_values(array_filter(
                        $this->messages,
                        static fn($item) => (string) ($item['id'] ?? '') !== $localId
                    ));
                    $clearedLocalIds[] = $localId;
                } else {
                $fingerprint = $this->messageFingerprint(
                    $message['text'] ?? '',
                    $message['mediaUrl'] ?? null,
                    $message['messageType'] ?? null
                );
                $localIds = $pendingMap[$fingerprint] ?? [];
                if (!empty($localIds)) {
                    $localId = array_shift($localIds);
                    $this->messages = array_values(array_filter(
                        $this->messages,
                        static fn($item) => (string) ($item['id'] ?? '') !== $localId
                    ));
                    $clearedLocalIds[] = $localId;
                    if (!empty($localIds)) {
                        $pendingMap[$fingerprint] = $localIds;
                    } else {
                        unset($pendingMap[$fingerprint]);
                    }
                }
                }
            }
            $newMessages[] = $message;
            if (($message['sender'] ?? '') !== 'support') {
                $this->newIncomingCount++;
            }
        }

        if (empty($newMessages)) {
            return;
        }

        $this->messages = array_values(array_merge($this->messages, $newMessages));
        usort($this->messages, static function (array $left, array $right): int {
            return ($left['createdAtTs'] ?? 0) <=> ($right['createdAtTs'] ?? 0);
        });

        if (!empty($clearedLocalIds) && !empty($this->pendingLocalMessages[$conversationId])) {
            $this->pendingLocalMessages[$conversationId] = array_values(array_filter(
                $this->pendingLocalMessages[$conversationId],
                static fn($entry) => !in_array($entry['id'] ?? '', $clearedLocalIds, true)
            ));
            if (empty($this->pendingLocalMessages[$conversationId])) {
                unset($this->pendingLocalMessages[$conversationId]);
            }
        }
    }

    private function messageFingerprint(string $text, ?string $mediaUrl, ?string $messageType): string
    {
        return md5(trim(mb_strtolower($text)) . '|' . ($mediaUrl ?? '') . '|' . ($messageType ?? ''));
    }

    public function markMessagesSeen(): void
    {
        $this->newIncomingCount = 0;
        $this->dispatch('scroll-chat-bottom');
    }

    public function pollConversations(): void
    {
        if ($this->loadingMessages || $this->loadingMoreMessages || $this->activeConversationId) {
            return;
        }
        $this->allConversations = [];
        $this->cachedConversations = [];
        $this->refreshConversationsCache();
    }

    private function loadConversationsFromRest(): array
    {
        $serviceAccount = $this->loadServiceAccount();
        if (!$serviceAccount) {
            return [];
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            Log::error('Firestore REST fallback failed: project_id missing in service account.');
            return [];
        }

        $token = $this->fetchAccessToken($serviceAccount);
        if (!$token) {
            return [];
        }

        try {
            $client = $this->makeHttpClient();
            $response = $client->get(
                "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat",
                [
                    'query' => [
                        'pageSize' => 100,
                        'orderBy' => 'lastMessageTime desc',
                    ],
                    'headers' => [
                        'Authorization' => "Bearer {$token}",
                    ],
                ]
            );

            $payload = json_decode((string) $response->getBody(), true);
            $documents = $payload['documents'] ?? [];
        } catch (\Throwable $e) {
            Log::error('Firestore REST fallback failed: ' . $e->getMessage());
            return [];
        }

        $rows = [];

        foreach ($documents as $doc) {
            $fields = $doc['fields'] ?? [];
            if (!$fields) {
                continue;
            }

            $userName = (string) $this->getFirestoreFieldValue($fields, 'userName', 'Unknown');
            $userId = (string) $this->getFirestoreFieldValue($fields, 'userId', '');
            $userType = (string) $this->getFirestoreFieldValue($fields, 'userType', '');
            $userImage = (string) $this->getFirestoreFieldValue($fields, 'userImage', '');
            $rawConversationType = (string) ($this->getFirestoreFieldValue($fields, 'conversationType')
                ?? $this->getFirestoreFieldValue($fields, 'conversation_type')
                ?? $this->getFirestoreFieldValue($fields, 'messageType')
                ?? $this->getFirestoreFieldValue($fields, 'message_type')
                ?? $this->getFirestoreFieldValue($fields, 'channel')
                ?? $this->getFirestoreFieldValue($fields, 'type')
                ?? '');
            $conversationType = $this->normalizeConversationType($rawConversationType);
            $docId = basename((string) ($doc['name'] ?? ''));
            $userImage = $this->normalizeConversationImage($userImage, $docId, null, $projectId, $token);

            $lastMessageTime = $this->normalizeTimestamp(
                $this->getFirestoreFieldValue($fields, 'lastMessageTime')
            );

            $unreadCount = 0;
            $unreadField = $this->getFirestoreFieldValue($fields, 'unreadCount', 0);
            if (is_array($unreadField)) {
                $unreadCount = (int) ($unreadField['support'] ?? 0);
            } else {
                $unreadCount = (int) $unreadField;
            }

            $rows[] = [
                'id' => $docId,
                'userName' => $userName,
                'userId' => $userId,
                'userType' => $userType,
                'userImage' => $userImage,
                'lastMessage' => (string) $this->getFirestoreFieldValue($fields, 'lastMessage', ''),
                'lastMessageTime' => $lastMessageTime?->diffForHumans(),
                'lastMessageAt' => $lastMessageTime?->timestamp ?? 0,
                'unreadCount' => $unreadCount,
                'conversationType' => $conversationType,
            ];
        }

        usort($rows, static function (array $left, array $right): int {
            return ($right['lastMessageAt'] ?? 0) <=> ($left['lastMessageAt'] ?? 0);
        });

        return $rows;
    }

    private function loadServiceAccount(): ?array
    {
        $serviceAccountPath = env('FIREBASE_CREDENTIALS', storage_path('firebase/firebase_credentials.json'));
        if (!file_exists($serviceAccountPath)) {
            Log::warning('Firebase credentials file not found at: ' . $serviceAccountPath);
            return null;
        }

        $raw = file_get_contents($serviceAccountPath);
        if ($raw === false) {
            Log::error('Unable to read Firebase credentials file.');
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            Log::error('Invalid Firebase credentials JSON.');
            return null;
        }

        return $data;
    }

    private function fetchAccessToken(array $serviceAccount): ?string
    {
        $cachedToken = Cache::get('firebase_access_token');
        if (is_string($cachedToken) && $cachedToken !== '') {
            return $cachedToken;
        }

        $clientEmail = $serviceAccount['client_email'] ?? null;
        $privateKey = $serviceAccount['private_key'] ?? null;
        $tokenUri = $serviceAccount['token_uri'] ?? 'https://oauth2.googleapis.com/token';

        if (!$clientEmail || !$privateKey) {
            Log::error('Firestore REST fallback failed: service account is missing client_email/private_key.');
            return null;
        }

        $now = time();
        $payload = [
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/datastore',
            'aud' => $tokenUri,
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwt = $this->encodeJwt($payload, $privateKey);
        if (!$jwt) {
            Log::error('Firestore REST fallback failed: unable to sign JWT.');
            return null;
        }

        try {
            $client = $this->makeHttpClient();
            $response = $client->post($tokenUri, [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ],
            ]);

            $tokenData = json_decode((string) $response->getBody(), true);
            $accessToken = $tokenData['access_token'] ?? null;
            if (!$accessToken) {
                Log::error('Firestore REST fallback failed: access token not returned.');
                return null;
            }

            $expiresIn = (int) ($tokenData['expires_in'] ?? 3600);
            Cache::put('firebase_access_token', $accessToken, now()->addSeconds(max(300, $expiresIn - 60)));

            return $accessToken;
        } catch (\Throwable $e) {
            Log::error('Firestore REST fallback failed: ' . $e->getMessage());
            return null;
        }
    }

    private function encodeJwt(array $payload, string $privateKey): ?string
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $segments = [
            $this->base64UrlEncode(json_encode($header)),
            $this->base64UrlEncode(json_encode($payload)),
        ];

        $data = implode('.', $segments);
        $signature = '';

        $success = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$success) {
            return null;
        }

        $segments[] = $this->base64UrlEncode($signature);
        return implode('.', $segments);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function normalizeConversationType(string $rawType): string
    {
        $normalized = str_replace(['_', ' '], '-', mb_strtolower(trim($rawType)));
        if ($normalized === '') {
            return 'chat';
        }
        if (in_array($normalized, ['email', 'emails'], true)) {
            return 'email';
        }
        if (in_array($normalized, ['chat', 'chats', 'support-chat', 'supportchat', 'support'], true)) {
            return 'chat';
        }
        return 'chat';
    }

    private function normalizeConversationImage(
        string $image,
        ?string $conversationId,
        $database = null,
        ?string $projectId = null,
        ?string $token = null
    ): string {
        $defaultImage = 'assets/images/avatar/default.png';
        $normalized = trim($image);

        if ($normalized === '' || $normalized === 'null') {
            if ($conversationId) {
                $this->updateConversationImage($conversationId, $defaultImage, $database, $projectId, $token);
            }
            return $defaultImage;
        }

        return $normalized;
    }

    private function updateConversationImage(
        string $conversationId,
        string $image,
        $database = null,
        ?string $projectId = null,
        ?string $token = null
    ): void {
        try {
            if ($database) {
                $database->collection('support_chat')
                    ->document($conversationId)
                    ->update([
                        ['path' => 'userImage', 'value' => $image],
                    ]);
                return;
            }

            if ($projectId && $token) {
                $client = $this->makeHttpClient();
                $client->patch(
                    "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}?updateMask.fieldPaths=userImage",
                    [
                        'headers' => [
                            'Authorization' => "Bearer {$token}",
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'fields' => [
                                'userImage' => ['stringValue' => $image],
                            ],
                        ],
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to update conversation userImage: ' . $e->getMessage());
        }
    }

    private function getFirestoreFieldValue(array $fields, string $key, $default = null)
    { 
        if (!isset($fields[$key])) {
            return $default;
        }

        return $this->decodeFirestoreValue($fields[$key], $default);
    }

    private function decodeFirestoreValue(array $value, $default = null)
    {
        if (array_key_exists('stringValue', $value)) {
            return $value['stringValue'];
        }
        if (array_key_exists('integerValue', $value)) {
            return (int) $value['integerValue'];
        }
        if (array_key_exists('doubleValue', $value)) {
            return (float) $value['doubleValue'];
        }
        if (array_key_exists('booleanValue', $value)) {
            return (bool) $value['booleanValue'];
        }
        if (array_key_exists('timestampValue', $value)) {
            return $value['timestampValue'];
        }
        if (array_key_exists('mapValue', $value)) {
            $fields = $value['mapValue']['fields'] ?? [];
            $result = [];
            foreach ($fields as $fieldKey => $fieldValue) {
                $result[$fieldKey] = $this->decodeFirestoreValue($fieldValue);
            }
            return $result;
        }
        if (array_key_exists('arrayValue', $value)) {
            $items = $value['arrayValue']['values'] ?? [];
            $result = [];
            foreach ($items as $item) {
                $result[] = $this->decodeFirestoreValue($item);
            }
            return $result;
        }

        return $default;
    }

    private function firestoreDatabase()
    {
        if (!extension_loaded('grpc')) {
             
            
            Log::warning('Firestore gRPC extension missing; using REST fallback.');
            return null;
        }
        $serviceAccountPath = env('FIREBASE_CREDENTIALS', storage_path('firebase/firebase_credentials.json'));
        if (!file_exists($serviceAccountPath)) {
             
            Log::warning('Firebase credentials file not found at: ' . $serviceAccountPath);
            return null;
        }

        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        return $factory->createFirestore()->database();
        try {
        } catch (\Throwable $e) {
            Log::error('Firestore init failed: ' . $e->getMessage());
            return null;
        }
    }

    private function makeHttpClient(): Client
    {
        $timeout = (int) env('FIREBASE_HTTP_TIMEOUT', 20);
        $connectTimeout = (int) env('FIREBASE_HTTP_CONNECT_TIMEOUT', 10);
        $proxy = env('FIREBASE_HTTP_PROXY');

        $options = [
            'timeout' => $timeout,
            'connect_timeout' => $connectTimeout,
        ];

        if ($proxy) {
            $options['proxy'] = $proxy;
        }

        return new Client($options);
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