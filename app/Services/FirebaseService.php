<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = storage_path('firebase/firebase_credentials.json');
        
        if (file_exists($serviceAccountPath)) {
            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $this->messaging = $factory->createMessaging();
        } else {
            Log::warning('Firebase credentials file not found at: ' . $serviceAccountPath);
        }
    }

    /**
     * Send a notification to a single device token
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return mixed
     */
    public function sendToToken(string $fcmToken, string $title, string $body, array $data = [])
    {
        if (!$this->messaging) {
            Log::error('Firebase Messaging not initialized. Check credentials file.');
            return false;
        }

        try {
            $notification = Notification::create($title, $body);
            
            // FCM Data values must be strings
            $formattedData = [];
            foreach ($data as $key => $value) {
                $formattedData[(string)$key] = (string)$value;
            }

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData($formattedData);

            return $this->messaging->send($message);
        } catch (\Throwable $e) {
            Log::error('FCM sendToToken error: ' . $e->getMessage(), [
                'token' => $fcmToken,
                'title' => $title,
            ]);
            return false;
        }
    }

    /**
     * Send notifications to multiple tokens
     *
     * @param array $fcmTokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToMany(array $fcmTokens, string $title, string $body, array $data = []): array
    {
        $results = [];
        foreach ($fcmTokens as $token) {
            try {
                $results[] = $this->sendToToken($token, $title, $body, $data);
            } catch (\Throwable $e) {
                $results[] = ['error' => $e->getMessage(), 'token' => $token];
            }
        }

        return $results;
    }
}
