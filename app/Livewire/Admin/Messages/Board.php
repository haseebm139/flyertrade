<?php

namespace App\Livewire\Admin\Messages;

use Illuminate\Support\Carbon;
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
    protected $queryString = [
        'filter' => ['except' => 'all'],
        'audience' => ['except' => 'service-users'],
    ];
    protected $updatesQueryString = ['filter', 'audience'];
    public array $cachedConversations = [];


    public array $messages = [];
    public bool $loadingMessages = false;
    public string $replyMessage = '';
    // Conversation
    public function sendReply(): void
    {
        if (!$this->activeConversationId || trim($this->replyMessage) === '') {
            return;
        }

        $database = $this->firestoreDatabase();

        try {
            $database
                ->collection('support_chat')
                ->document($this->activeConversationId)
                ->collection('messages')
                ->add([
                    'text' => $this->replyMessage,
                    'senderType' => 'support',
                    'createdAt' => new Timestamp(new \DateTime()),
                    'seen' => false,
                ]);

            // update parent doc
            $database->collection('support_chat')
                ->document($this->activeConversationId)
                ->update([
                    ['path' => 'lastMessage', 'value' => $this->replyMessage],
                    ['path' => 'lastMessageTime', 'value' => now()],
                    ['path' => 'unreadCount.support', 'value' => 0],
                ]);

            $this->replyMessage = '';
            $this->loadMessages($this->activeConversationId);

        } catch (\Throwable $e) {
            Log::error('Send reply failed: ' . $e->getMessage());
        }
    }

    private function loadMessages(string $conversationId): void
    {
        $this->loadingMessages = true;
        $this->messages = [];
        
        $database = $this->firestoreDatabase();
        
        dd($database);
        if ($database) {
            $documents = $database
            ->collection('support_chat')
            ->document($conversationId)
            ->collection('messages')
            ->orderBy('createdAt')
            ->documents();
            
            dd($documents);
            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                $this->messages[] = [
                    'id' => $doc->id(),
                    'text' => $data['text'] ?? '',
                    'sender' => $data['senderType'] ?? 'user',
                    'time' => $this->normalizeTimestamp($data['createdAt'])?->diffForHumans(),
                ];
            }
        }
        try {
        } catch (\Throwable $e) {
            Log::error('Load messages failed: ' . $e->getMessage());
        }

        $this->loadingMessages = false;
    }    
    public function selectConversation(string $conversationId): void
    {
         
        if ($this->activeConversationId === $conversationId) {
            return;
        }

        $this->activeConversationId = $conversationId;

        $this->dispatch('scroll-chat-bottom');

        $this->loadMessages($conversationId);
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
    }
    public function render()
    {

        if (empty($this->cachedConversations)) {
            $this->cachedConversations = $this->loadConversations();
        }

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

        $this->cachedConversations = [];
    }
    private function loadConversations(): array
    {
        $database = $this->firestoreDatabase();
         
        if (!$database) {
            return $this->loadConversationsFromRest();
        }

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
                return $this->loadConversationsFromRest();
            }
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
            $rawConversationType = (string) ($data['conversationType']
                ?? $data['conversation_type']
                ?? $data['messageType']
                ?? $data['message_type']
                ?? $data['channel']
                ?? $data['type']
                ?? '');
            $conversationType = $this->normalizeConversationType($rawConversationType);
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

            $lastMessageTime = $this->normalizeTimestamp($data['lastMessageTime'] ?? null);
            $unreadCount = 0;
            if (isset($data['unreadCount'])) {
                if (is_array($data['unreadCount'])) {
                    $unreadCount = (int) ($data['unreadCount']['support'] ?? 0);
                } else {
                    $unreadCount = (int) $data['unreadCount'];
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
            ];
        }

        if ($usedFallback) {
            usort($rows, static function (array $left, array $right): int {
                return ($right['lastMessageAt'] ?? 0) <=> ($left['lastMessageAt'] ?? 0);
            });
        }

        foreach ($rows as $index => $row) {
            unset($rows[$index]['lastMessageAt']);
        }

        return $rows;
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

        $search = trim(mb_strtolower($this->search));
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

            if ($this->filter === 'unread' && $unreadCount === 0) {
                continue;
            }
            if ($this->filter === 'emails' && $conversationType !== 'email') {
                continue;
            }
            if ($this->filter === 'chats' && $conversationType !== 'chat') {
                continue;
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
            ];
        }

        usort($rows, static function (array $left, array $right): int {
            return ($right['lastMessageAt'] ?? 0) <=> ($left['lastMessageAt'] ?? 0);
        });

        foreach ($rows as $index => $row) {
            unset($rows[$index]['lastMessageAt']);
        }

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
            dd(storage_path('firebase/firebase_credentials.json'));
            
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
