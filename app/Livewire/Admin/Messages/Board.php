<?php

namespace App\Livewire\Admin\Messages;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Kreait\Firebase\Factory;
use Google\Cloud\Core\Timestamp;
use GuzzleHttp\Client;

class Board extends Component
{
    public string $search = '';
    public string $filter = 'all';
    public string $audience = 'service-users';
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
    public string $replyMessage = '';
    public ?string $replyMediaUrl = null;
    public ?string $replyMediaType = null;
    public bool $selectAll = false;
    public string $filterStatus = 'all';
    // Conversation
    public function sendReply(): void
    {
        $messageText = trim($this->replyMessage);
        if (!$this->activeConversationId || ($messageText === '' && !$this->replyMediaUrl)) {
            return;
        }

        $database = $this->firestoreDatabase();

        try {
            $senderName = auth()->user()?->name ?? 'Support Team';
            $senderId = auth()->id();
            $senderImage = 'assets/images/avatar/default.png';
            $receiverId = $this->activeConversationMeta['userId'] ?? null;
            $receiverName = $this->activeConversationMeta['userName'] ?? 'Support Team';
            $receiverImage = $this->activeConversationMeta['userImage'] ?? null;
            $messageType = $this->replyMediaType ?: 'text';

            if ($database) {
                $payload = [
                    'message' => $messageText,
                    'messageType' => $messageType,
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
                if ($this->replyMediaType) {
                    $payload['mediaType'] = $this->replyMediaType;
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
                    $this->replyMediaType
                );
            }

            $this->messages[] = [
                'id' => 'local-' . uniqid(),
                'text' => $messageText,
                'sender' => 'support',
                'mediaUrl' => $this->replyMediaUrl,
                'messageType' => $messageType,
                'time' => now()->diffForHumans(),
            ];
            $this->replyMessage = '';
            $this->replyMediaUrl = null;
            $this->replyMediaType = null;
            $this->cachedConversations = [];
            $this->allConversations = [];
            $this->dispatch('scroll-chat-bottom');
            $this->loadMessages($this->activeConversationId);
        } catch (\Throwable $e) {
            Log::error('Send reply failed: ' . $e->getMessage());
        }
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
        ?string $mediaType = null
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
    private function loadMessagesFromRest(string $conversationId): void
    {
        
        $this->loadingMessages = true;
        $this->messages = [];

        $serviceAccount = $this->loadServiceAccount();
        if (!$serviceAccount) {
            $this->loadingMessages = false;
            return;
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            $this->loadingMessages = false;
            return;
        }

        $token = $this->fetchAccessToken($serviceAccount);
        if (!$token) {
            $this->loadingMessages = false;
            return;
        }

        $client = $this->makeHttpClient();
        $response = $client->get(
            "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_chat/{$conversationId}/messages",
            [
                'query' => ['pageSize' => 30, 'orderBy' => 'createdAt desc'],
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
                $messageType = (string) $this->getFirestoreFieldValue($fields, 'messageType', ($mediaUrl ? 'media' : 'text'));
                $senderName = (string) $this->getFirestoreFieldValue($fields, 'senderName', '');
                $senderType = (string) $this->getFirestoreFieldValue($fields, 'senderType', $senderName === 'Support Team' ? 'support' : 'user');

                $message = [
                    'id' => basename((string) ($doc['name'] ?? '')),
                    'text' => $messageText,
                    'sender' => $senderType,
                    'mediaUrl' => $mediaUrl,
                    'messageType' => $messageType,
                    'time' => $this->normalizeTimestamp($createdAt)?->diffForHumans(),
                ];
                $messages[] = $message;
            } catch (\Throwable $e) {
                Log::error('Load messages REST fallback failed: ' . $e->getMessage());
            }
        }

        $this->messages = array_reverse($messages);
        $this->loadingMessages = false;
    }

    private function loadMessages(string $conversationId): void
    {
        $this->loadingMessages = true;
        $this->messages = [];
        
        $database = $this->firestoreDatabase();
         
        if ($database) {
            try {
                $documents = $database
                    ->collection('support_chat')
                    ->document($conversationId)
                    ->collection('messages')
                    ->orderBy('createdAt', 'DESC')
                    ->limit(30)
                    ->documents();

                $messages = [];

                foreach ($documents as $doc) {
                    if (!$doc->exists()) continue;

                    $data = $doc->data();

                $messageText = $data['message'] ?? $data['text'] ?? '';
                $messageType = $data['messageType'] ?? ($data['mediaUrl'] ?? null ? 'media' : 'text');
                $mediaUrl = $data['mediaUrl'] ?? $data['mediaURL'] ?? null;
                $senderType = $data['senderType']
                    ?? (($data['senderName'] ?? '') === 'Support Team' ? 'support' : 'user');

                $messages[] = [
                    'id' => $doc->id(),
                    'text' => $messageText,
                    'sender' => $senderType,
                    'mediaUrl' => $mediaUrl,
                    'messageType' => $messageType,
                    'time' => $this->normalizeTimestamp($data['createdAt'])?->diffForHumans(),
                ];
                }

                $this->messages = array_reverse($messages);
                $this->loadingMessages = false;
                return; // success, no need REST
            } catch (\Throwable $e) {
                Log::warning('Load messages gRPC failed, using REST fallback: ' . $e->getMessage());
            }
        } else {
            Log::warning('Firestore gRPC missing, using REST fallback for messages.');
        }

        // Fallback to REST
        $this->loadMessagesFromRest($conversationId);
    }    
    public function selectConversation(string $conversationId): void
    {
        if ($this->activeConversationId === $conversationId) {
            return;
        }

        $this->activeConversationId = $conversationId;
        $this->activeConversationMeta = $this->resolveActiveConversationMeta($conversationId);

        $this->dispatch('scroll-chat-bottom');
        $this->markConversationRead($conversationId);
        $this->loadMessages($conversationId);
    }

    public function closeConversation(): void
    {
        $this->activeConversationId = null;
        $this->messages = [];
    }

    public function clearAttachment(): void
    {
        $this->replyMediaUrl = null;
        $this->replyMediaType = null;
    }

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

            $this->cachedConversations = [];
            $this->allConversations = [];
        } catch (\Throwable $e) {
            Log::warning('Mark conversation read failed: ' . $e->getMessage());
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
        $this->cachedConversations = [];
    }
     
    public function getHasActiveConversationProperty(): bool
    {
        return !empty($this->activeConversationId);
    }

    public function refreshInbox(): void
    {
        $this->cachedConversations = [];
        $this->allConversations = [];
    }
    public function render()
    {

        if (empty($this->cachedConversations)) {
            $this->cachedConversations = $this->loadConversations();
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

        $this->cachedConversations = [];
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
                            'userImage' => (string) ($data['userImage'] ?? 'assets/images/avatar/default.png'),
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
        if ($this->activeConversationId && !$this->loadingMessages) {
            $this->loadMessages($this->activeConversationId);
        }
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
            $rawConversationType = (string) ($this->getFirestoreFieldValue($fields, 'conversationType')
                ?? $this->getFirestoreFieldValue($fields, 'conversation_type')
                ?? $this->getFirestoreFieldValue($fields, 'messageType')
                ?? $this->getFirestoreFieldValue($fields, 'message_type')
                ?? $this->getFirestoreFieldValue($fields, 'channel')
                ?? $this->getFirestoreFieldValue($fields, 'type')
                ?? '');
            $conversationType = $this->normalizeConversationType($rawConversationType);

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
                'id' => basename((string) ($doc['name'] ?? '')),
                'userName' => $userName,
                'userId' => $userId,
                'userType' => $userType,
                'userImage' => (string) $this->getFirestoreFieldValue($fields, 'userImage', 'assets/images/avatar/default.png'),
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
