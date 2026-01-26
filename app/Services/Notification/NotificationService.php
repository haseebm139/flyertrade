<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\User;
use App\Helpers\NotificationIcon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;

class NotificationService
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send FCM push notification (Legacy method kept for compatibility or internal use)
     */
    public function sendPushNotification($fcmToken, $title, $message, array $data = [])
    {
        return $this->firebaseService->sendToToken($fcmToken, $title, $message, $data);
    }

    /**
     * Send notification to a single user
     *
     * @param User $user
     * @param string $type
     * @param string $title
     * @param string $message
     * @param string $recipientType
     * @param mixed $notifiable
     * @param array $data
     * @return Notification
     */
    public function send(
        User $user,
        string $type,
        string $title,
        string $message,
        string $recipientType = 'customer',
        $notifiable = null,
        array $data = [],
        ?string $icon = null,
        ?string $category = null
    ): Notification {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'icon' => $icon ?? NotificationIcon::getIconForType($type),
            'category' => $category ?? NotificationIcon::getCategoryForType($type),
            'title' => $title,
            'message' => $message,
            'recipient_type' => $recipientType,
            'notifiable_type' => $notifiable ? get_class($notifiable) : null,
            'notifiable_id' => $notifiable ? $notifiable->id : null,
            'data' => $data,
        ]);

        // Send FCM push notification if user has a token
        if ($user->fcm_token) {
            $this->sendPushNotification($user->fcm_token, $title, $message, array_merge($data, [
                'notification_id' => $notification->id,
                'type' => $type
            ]));
        }

        return $notification;
    }

    /**
     * Send notification to multiple users
     *
     * @param array $userIds
     * @param string $type
     * @param string $title
     * @param string $message
     * @param string $recipientType
     * @param mixed $notifiable
     * @param array $data
     * @return int Number of notifications created
     */
    public function sendToMany(
        array $userIds,
        string $type,
        string $title,
        string $message,
        string $recipientType = 'customer',
        $notifiable = null,
        array $data = [],
        ?string $icon = null,
        ?string $category = null
    ): int {
        if (empty($userIds)) {
            \Log::warning('sendToMany called with empty userIds array');
            return 0;
        }

        // Only set notifiable if provided, otherwise leave them out (will be null in DB)
        $notifiableType = $notifiable ? get_class($notifiable) : null;
        $notifiableId = $notifiable ? $notifiable->id : null;
        $icon = $icon ?? NotificationIcon::getIconForType($type);
        $category = $category ?? NotificationIcon::getCategoryForType($type);

        $count = 0;
        $errors = [];

        // Use individual create() instead of bulk insert for better reliability
        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                if (!$user) continue;

                $notificationData = [
                    'user_id' => $userId,
                    'type' => $type,
                    'icon' => $icon,
                    'category' => $category,
                    'title' => $title,
                    'message' => $message,
                    'recipient_type' => $recipientType,
                    'data' => $data, // Eloquent will automatically cast to JSON
                ];
                
                // Only add notifiable fields if notifiable is provided
                if ($notifiableType && $notifiableId) {
                    $notificationData['notifiable_type'] = $notifiableType;
                    $notificationData['notifiable_id'] = $notifiableId;
                }
                
                $notification = Notification::create($notificationData);
                $count++;

                // Send FCM push notification if user has a token
                if ($user->fcm_token) {
                    $this->sendPushNotification($user->fcm_token, $title, $message, array_merge($data, [
                        'notification_id' => $notification->id,
                        'type' => $type
                    ]));
                }
            } catch (\Exception $e) {
                $errors[] = "User ID {$userId}: " . $e->getMessage();
                \Log::error("Failed to create notification for user {$userId}: " . $e->getMessage());
                \Log::error("Stack trace: " . $e->getTraceAsString());
            }
        }

        if ($count > 0) {
            \Log::info("Successfully created {$count} notifications of type '{$type}'");
        }

        if (!empty($errors)) {
            \Log::warning('Some notifications failed to create: ' . implode('; ', $errors));
        }

        return $count;
    }

    /**
     * Send notification to all admins
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param mixed $notifiable
     * @param array $data
     * @return int
     */
    public function sendToAdmins(
        string $type,
        string $title,
        string $message,
        $notifiable = null,
        array $data = [],
        ?string $icon = null,
        ?string $category = null
    ): int {
        // Get admin IDs - check both role_id and user_type
        $adminIds = User::where(function($query) {
                $query->where('role_id', 'admin')
                      ->orWhere('user_type', 'admin');
            })
            ->pluck('id')
            ->toArray();

        if (empty($adminIds)) {
            \Log::warning('No admin users found in database. Cannot send admin notification.');
            return 0;
        }

        \Log::info('Sending notification to ' . count($adminIds) . ' admin(s): ' . implode(', ', $adminIds));
        
        return $this->sendToMany($adminIds, $type, $title, $message, 'admin', $notifiable, $data, $icon, $category);
    }

    /**
     * Send notification to all providers
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param mixed $notifiable
     * @param array $data
     * @return int
     */
    public function sendToProviders(
        string $type,
        string $title,
        string $message,
        $notifiable = null,
        array $data = []
    ): int {
        $providerIds = User::where('role_id', 'provider')
            ->orWhere('user_type', 'provider')
            ->orWhere('user_type', 'multi')
            ->pluck('id')
            ->toArray();

        if (empty($providerIds)) {
            return 0;
        }

        return $this->sendToMany($providerIds, $type, $title, $message, 'provider', $notifiable, $data);
    }

    /**
     * Send notification to all customers
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param mixed $notifiable
     * @param array $data
     * @return int
     */
    public function sendToCustomers(
        string $type,
        string $title,
        string $message,
        $notifiable = null,
        array $data = []
    ): int {
        $customerIds = User::where('role_id', 'customer')
            ->orWhere('user_type', 'customer')
            ->orWhere('user_type', 'multi')
            ->pluck('id')
            ->toArray();

        if (empty($customerIds)) {
            return 0;
        }

        return $this->sendToMany($customerIds, $type, $title, $message, 'customer', $notifiable, $data);
    }

    /**
     * Send booking created notification
     */
    public function notifyBookingCreated($booking): void
    {
        // Notify provider
        $provider = User::find($booking->provider_id);
        if ($provider) {
            $this->send(
                $provider,
                'booking_created',
                'New Booking Request',
                "You have a new booking request from {$booking->customer->name}",
                'provider',
                $booking,
                ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
            );
        }

        // Notify customer
        $customer = User::find($booking->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'booking_created',
                'Booking Created',
                "Your booking #{$booking->booking_ref} has been created successfully",
                'customer',
                $booking,
                ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
            );
        }

        // Notify admin
        $this->sendToAdmins(
            'booking_created',
            'New Booking Created',
            "New booking #{$booking->booking_ref} has been created",
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'action_url' => route('booking.index')
            ]
        );
    }

    /**
     * Send booking confirmed notification
     */
    public function notifyBookingConfirmed($booking): void
    {
        $customer = User::find($booking->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'booking_confirmed',
                'Booking Confirmed',
                "Your booking #{$booking->booking_ref} has been confirmed by the provider",
                'customer',
                $booking,
                ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
            );
        }
    }

    /**
     * Send booking cancelled notification
     */
    public function notifyBookingCancelled($booking, $cancelledBy = 'customer'): void
    {
        if ($cancelledBy === 'customer') {
            // Notify provider
            $provider = User::find($booking->provider_id);
            if ($provider) {
                $this->send(
                    $provider,
                    'booking_cancelled',
                    'Booking Cancelled',
                    "Booking #{$booking->booking_ref} has been cancelled by the customer",
                    'provider',
                    $booking,
                    ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
                );
            }
        } else {
            // Notify customer
            $customer = User::find($booking->customer_id);
            if ($customer) {
                $this->send(
                    $customer,
                    'booking_cancelled',
                    'Booking Cancelled',
                    "Your booking #{$booking->booking_ref} has been cancelled by the provider",
                    'customer',
                    $booking,
                    ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
                );
            }
        }

        // Notify admin
        $this->sendToAdmins(
            'booking_cancelled',
            'Booking Cancelled',
            "Booking #{$booking->booking_ref} has been cancelled",
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'action_url' => route('booking.index')
            ]
        );
    }

    /**
     * Send payment success notification
     */
    public function notifyPaymentSuccess($transaction): void
    {
        $customer = User::find($transaction->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'payment_success',
                'Payment Successful',
                "Payment of {$transaction->currency} {$transaction->amount} for booking has been processed successfully",
                'customer',
                $transaction,
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'booking_id' => $transaction->booking_id,
                ]
            );
        }

        // Notify provider
        $provider = User::find($transaction->provider_id);
        if ($provider) {
            $this->send(
                $provider,
                'payment_success',
                'Payment Received',
                "You have received payment of {$transaction->currency} {$transaction->net_amount}",
                'provider',
                $transaction,
                [
                    'transaction_id' => $transaction->id,
                    'net_amount' => $transaction->net_amount,
                    'currency' => $transaction->currency,
                    'booking_id' => $transaction->booking_id,
                ]
            );
        }
    }

    /**
     * Send payment failed notification
     */
    public function notifyPaymentFailed($transaction): void
    {
        $customer = User::find($transaction->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'payment_failed',
                'Payment Failed',
                "Payment of {$transaction->currency} {$transaction->amount} failed. Please try again.",
                'customer',
                $transaction,
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'failure_reason' => $transaction->failure_reason,
                ]
            );
        }
    }

    /**
     * Send review received notification
     */
    public function notifyReviewReceived($review): void
    {
        $provider = User::find($review->receiver_id);
        if ($provider) {
            $this->send(
                $provider,
                'review_received',
                'New Review Received',
                "You have received a {$review->rating}-star review",
                'provider',
                $review,
                [
                    'review_id' => $review->id,
                    'rating' => $review->rating,
                    'service_id' => $review->service_id,
                ]
            );
        }
    }

    /**
     * Notify admin about document verification pending
     */
    public function notifyDocumentVerificationPending(int $count = 0, ?int $providerId = null): int
    {

        $actionUrl = $providerId
            ? route('user-management.service.providers.view', ['id' => $providerId])
            : route('user-management.service.providers.index');
        $extraData = ['count' => $count, 'action_url' => $actionUrl];
        if ($providerId) {
            $extraData['provider_id'] = $providerId;
        }

        return $this->sendToAdmins(
            'document_verification',
            'Document verification',
            $count > 0 ? "{$count} New Providers Awaiting Document Verification." : "New Providers Awaiting Document Verification.",
            null,
            $extraData,
            NotificationIcon::DOCUMENT_VERIFICATION,
            'admin_actions'
        );
    }

    /**
     * Notify admin about high cancellation alert
     */
    public function notifyHighCancellationAlert(string $providerName, int $cancellationCount, int $providerId): int
    {
        return $this->sendToAdmins(
            'high_cancellation_alert',
            'High Cancellation Alert',
            "{$providerName} has {$cancellationCount} cancellations this week.",
            null,
            [
                'provider_id' => $providerId,
                'provider_name' => $providerName,
                'cancellation_count' => $cancellationCount,
                'action_url' => route('user-management.service.providers.view', ['id' => $providerId])
            ],
            NotificationIcon::HIGH_CANCELLATION_ALERT,
            'admin_actions'
        );
    }

    /**
     * Notify admin about provider late escalation
     */
    public function notifyProviderLateEscalation($booking): int
    {
        return $this->sendToAdmins(
            'provider_late_escalation',
            'Provider Late Escalation',
            "Customer #{$booking->customer_id} has escalated a late provider issue for booking #{$booking->booking_ref}.",
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'customer_id' => $booking->customer_id,
                'provider_id' => $booking->provider_id,
                'action_url' => route('booking.index')
            ],
            NotificationIcon::ESCALATION,
            'admin_actions'
        );
    }

    /**
     * Notify admin about new booking created
     */
    public function notifyNewBookingCreated($booking): void
    {
        $customer = User::find($booking->customer_id);
        $provider = User::find($booking->provider_id);
        $serviceName = 'Service';
        
        if ($booking->providerService && $booking->providerService->service) {
            $serviceName = $booking->providerService->service->name;
        }
        
        $customerName = $customer ? $customer->name : 'Customer';
        $providerName = $provider ? $provider->name : 'Provider';
        
        $this->sendToAdmins(
            'booking_created',
            'New Booking Created:',
            "{$customerName} just booked {$serviceName} with {$providerName}.",
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'customer_id' => $booking->customer_id,
                'provider_id' => $booking->provider_id,
                'service_name' => $serviceName,
                'action_url' => route('booking.index')
            ],
            NotificationIcon::BOOKING_CREATED,
            'bookings'
        );
    }

    /**
     * Notify admin about new service provider registered
     */
    public function notifyNewProviderRegistered(User $provider): void
    {
        $serviceName = 'Service Provider';
        
        // Get provider's primary service if available
        if ($provider->providerServices && $provider->providerServices->isNotEmpty()) {
            $primaryService = $provider->providerServices->where('is_primary', true)->first();
            if ($primaryService && $primaryService->service) {
                $serviceName = $primaryService->service->name;
            } elseif ($provider->providerServices->first() && $provider->providerServices->first()->service) {
                $serviceName = $provider->providerServices->first()->service->name;
            }
        }
        
        $this->sendToAdmins(
            'provider_registered',
            'New Service Provider Registered:',
            "{$provider->name} just signed up as a {$serviceName}",
            $provider,
            [
                'provider_id' => $provider->id,
                'provider_name' => $provider->name,
                'service_name' => $serviceName,
                'action_url' => route('user-management.service.providers.view', ['id' => $provider->id])
            ],
            NotificationIcon::PROVIDER_REGISTERED,
            'admin_actions'
        );
    }

    /**
     * Notify admin about new dispute
     */
    public function notifyNewDispute($dispute, $booking = null): void
    {
        $bookingRef = $booking ? $booking->booking_ref : ($dispute->booking_ref ?? 'N/A');
        $reason = $dispute->reason ?? 'Unknown reason';
        
        $this->sendToAdmins(
            'dispute_created',
            'New dispute:',
            "Dispute raised for Booking #{$bookingRef} - Reason - {$reason}",
            $dispute,
            [
                'dispute_id' => $dispute->id,
                'booking_id' => $dispute->booking_id ?? null,
                'booking_ref' => $bookingRef,
                'reason' => $reason,
                'action_url' => route('dispute.index')
            ],
            NotificationIcon::DISPUTE,
            'admin_actions'
        );
    }

    /**
     * Notify admin about new review posted
     */
    public function notifyNewReviewPosted($review): void
    {
        $reviewer = User::find($review->reviewer_id ?? $review->user_id);
        $provider = User::find($review->receiver_id);
        $serviceName = 'Service';
        
        if ($review->service) {
            $serviceName = $review->service->name;
        }
        
        $reviewerName = $reviewer ? $reviewer->name : 'Customer';
        $providerName = $provider ? $provider->name : 'Provider';
        
        $this->sendToAdmins(
            'review_received',
            'New Review Posted:',
            "{$reviewerName} rated {$providerName} '{$serviceName}' service {$review->rating} stars",
            $review,
            [
                'review_id' => $review->id,
                'reviewer_id' => $review->reviewer_id ?? $review->user_id,
                'provider_id' => $review->receiver_id,
                'rating' => $review->rating,
                'service_name' => $serviceName,
                'action_url' => route('reviews.show', ['id' => $review->id])
            ],
            NotificationIcon::REVIEW_RECEIVED,
            'reviews'
        );
    }

    /**
     * Notify customer about job completion
     */
    public function notifyJobCompleted($booking): void
    {
        $customer = User::find($booking->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'job_completed',
                'Job completed',
                "Don't forget to rate your service provider.",
                'customer',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'provider_id' => $booking->provider_id,
                    'action_url' => "/bookings/{$booking->id}/review"
                ],
                NotificationIcon::JOB_COMPLETED,
                'bookings'
            );
        }
    }

    /**
     * Notify customer about special offer
     */
    public function notifySpecialOffer(User $customer, string $offerMessage, ?int $discountPercent = null, array $data = []): void
    {
        $title = $discountPercent ? "Today's special offer" : "Special Offer";
        $message = $discountPercent 
            ? "Get {$discountPercent}% discount for your booking today!"
            : $offerMessage;

        $this->send(
            $customer,
            'special_offer',
            $title,
            $message,
            'customer',
            null,
            array_merge([
                'discount_percent' => $discountPercent,
                'action_url' => '/offers'
            ], $data),
            NotificationIcon::SPECIAL_OFFER,
            'promotions'
        );
    }

    /**
     * Notify customer about promotion
     */
    public function notifyPromotion(User $customer, string $promotionTitle, string $promotionMessage, array $data = []): void
    {
        $this->send(
            $customer,
            'promotion',
            $promotionTitle,
            $promotionMessage,
            'customer',
            null,
            array_merge([
                'action_url' => '/promotions'
            ], $data),
            NotificationIcon::PROMOTION,
            'promotions'
        );
    }

    /**
     * Notify customer about new service available
     */
    public function notifyNewService(User $customer, string $serviceName, string $area = null, array $data = []): void
    {
        $message = $area 
            ? "{$serviceName} now available in your area."
            : "{$serviceName} now available.";

        $this->send(
            $customer,
            'new_service',
            'New service!',
            $message,
            'customer',
            null,
            array_merge([
                'service_name' => $serviceName,
                'area' => $area,
                'action_url' => '/services'
            ], $data),
            NotificationIcon::NEW_SERVICE,
            'services'
        );
    }

    /**
     * Notify customer about upcoming booking reminder
     */
    public function notifyBookingReminder($booking, string $timeUntil = null): void
    {
        $customer = User::find($booking->customer_id);
        if (!$customer) {
            return;
        }

        $providerName = $booking->provider->name ?? 'your service provider';
        $message = $timeUntil 
            ? "Your booking with {$providerName} is coming up in {$timeUntil}."
            : "Your booking with {$providerName} is coming up soon.";

        $this->send(
            $customer,
            'booking_reminder',
            'Reminder',
            $message,
            'customer',
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'provider_id' => $booking->provider_id,
                'provider_name' => $providerName,
                'time_until' => $timeUntil,
                'action_url' => "/bookings/{$booking->id}"
            ],
            NotificationIcon::BOOKING_REMINDER,
            'bookings'
        );
    }

    /**
     * Notify customer about payment success (mobile app style)
     */
    public function notifyPaymentSuccessful($transaction): void
    {
        $customer = User::find($transaction->customer_id);
        if (!$customer) {
            return;
        }

        // Get service name from booking if available
        $serviceName = 'your service';
        if ($transaction->booking && $transaction->booking->providerService) {
            $serviceName = $transaction->booking->providerService->service->name ?? 'your service';
        }

        $this->send(
            $customer,
            'payment_successful',
            'Payment Successful',
            "You have made payment for your {$serviceName}.",
            'customer',
            $transaction,
            [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'booking_id' => $transaction->booking_id,
                'action_url' => "/transactions/{$transaction->id}"
            ],
            NotificationIcon::PAYMENT_SUCCESS,
            'transactions'
        );
    }

    /**
     * Notify customer when booking is rejected by provider
     */
    public function notifyBookingRejected($booking): void
    {
        $customer = User::find($booking->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'booking_rejected',
                'Booking Rejected',
                "Your booking #{$booking->booking_ref} has been rejected by the provider",
                'customer',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'provider_id' => $booking->provider_id,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::BOOKING_CANCELLED,
                'bookings'
            );
        }

        // Notify admin
        $this->sendToAdmins(
            'booking_rejected',
            'Booking Rejected',
            "Booking #{$booking->booking_ref} has been rejected by provider",
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'customer_id' => $booking->customer_id,
                'provider_id' => $booking->provider_id,
                'action_url' => route('booking.index')
            ],
            NotificationIcon::BOOKING_CANCELLED,
            'bookings'
        );
    }

    /**
     * Notify customer when provider starts the booking
     */
    public function notifyBookingStarted($booking): void
    {
        $customer = User::find($booking->customer_id);
        if ($customer) {
            $providerName = $booking->provider->name ?? 'Provider';
            $this->send(
                $customer,
                'booking_started',
                'Service Started',
                "{$providerName} has started your booking #{$booking->booking_ref}",
                'customer',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'provider_id' => $booking->provider_id,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::BOOKING_CONFIRMED,
                'bookings'
            );
        }
    }

    /**
     * Notify when reschedule is requested
     */
    public function notifyRescheduleRequested($booking, $reschedule, $requestedBy = 'customer'): void
    {
        if ($requestedBy === 'customer') {
            // Notify provider
            $provider = User::find($booking->provider_id);
            if ($provider) {
                $this->send(
                    $provider,
                    'reschedule_requested',
                    'Reschedule Request',
                    "Customer has requested to reschedule booking #{$booking->booking_ref}",
                    'provider',
                    $booking,
                    [
                        'booking_id' => $booking->id,
                        'booking_ref' => $booking->booking_ref,
                        'reschedule_id' => $reschedule->id,
                        'action_url' => "/bookings/{$booking->id}/reschedule"
                    ],
                    NotificationIcon::RESCHEDULE_REQUEST,
                    'bookings'
                );
            }
        } else {
            // Notify customer
            $customer = User::find($booking->customer_id);
            if ($customer) {
                $this->send(
                    $customer,
                    'reschedule_requested',
                    'Reschedule Request',
                    "Provider has requested to reschedule booking #{$booking->booking_ref}",
                    'customer',
                    $booking,
                    [
                        'booking_id' => $booking->id,
                        'booking_ref' => $booking->booking_ref,
                        'reschedule_id' => $reschedule->id,
                        'action_url' => "/bookings/{$booking->id}/reschedule"
                    ],
                    NotificationIcon::RESCHEDULE_REQUEST,
                    'bookings'
                );
            }
        }
    }

    /**
     * Notify when reschedule is accepted
     */
    public function notifyRescheduleAccepted($booking, $reschedule): void
    {
        $customer = User::find($booking->customer_id);
        $provider = User::find($booking->provider_id);

        if ($customer) {
            $this->send(
                $customer,
                'reschedule_accepted',
                'Reschedule Accepted',
                "Your reschedule request for booking #{$booking->booking_ref} has been accepted",
                'customer',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'reschedule_id' => $reschedule->id,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::RESCHEDULE_ACCEPTED,
                'bookings'
            );
        }

        if ($provider) {
            $this->send(
                $provider,
                'reschedule_accepted',
                'Reschedule Accepted',
                "Reschedule request for booking #{$booking->booking_ref} has been accepted",
                'provider',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'reschedule_id' => $reschedule->id,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::RESCHEDULE_ACCEPTED,
                'bookings'
            );
        }
    }

    /**
     * Notify when reschedule is rejected
     */
    public function notifyRescheduleRejected($booking, $reschedule): void
    {
        $customer = User::find($booking->customer_id);
        $provider = User::find($booking->provider_id);

        if ($customer) {
            $this->send(
                $customer,
                'reschedule_rejected',
                'Reschedule Rejected',
                "Your reschedule request for booking #{$booking->booking_ref} has been rejected",
                'customer',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'reschedule_id' => $reschedule->id,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::RESCHEDULE_REJECTED,
                'bookings'
            );
        }

        if ($provider) {
            $this->send(
                $provider,
                'reschedule_rejected',
                'Reschedule Rejected',
                "Reschedule request for booking #{$booking->booking_ref} has been rejected",
                'provider',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'reschedule_id' => $reschedule->id,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::RESCHEDULE_REJECTED,
                'bookings'
            );
        }
    }

    /**
     * Notify when booking expires (auto-rejected)
     */
    public function notifyBookingExpired($booking): void
    {
        $customer = User::find($booking->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'booking_rejected',
                'Booking Expired',
                "Your booking #{$booking->booking_ref} has expired as provider did not respond in time",
                'customer',
                $booking,
                [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'action_url' => "/bookings/{$booking->id}"
                ],
                NotificationIcon::BOOKING_CANCELLED,
                'bookings'
            );
        }

        // Notify admin
        $this->sendToAdmins(
            'booking_rejected',
            'Booking Expired',
            "Booking #{$booking->booking_ref} has expired (auto-rejected)",
            $booking,
            [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'action_url' => route('booking.index')
            ],
            NotificationIcon::BOOKING_CANCELLED,
            'bookings'
        );
    }

    /**
     * Notify when refund is processed
     */
    public function notifyRefundProcessed($transaction): void
    {
        $customer = User::find($transaction->customer_id);
        if ($customer) {
            $this->send(
                $customer,
                'refund_processed',
                'Refund Processed',
                "Refund of {$transaction->currency} {$transaction->amount} has been processed successfully",
                'customer',
                $transaction,
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'booking_id' => $transaction->booking_id,
                    'action_url' => "/transactions/{$transaction->id}"
                ],
                NotificationIcon::REFUND_PROCESSED,
                'transactions'
            );
        }

        // Notify admin
        $bookingRef = $transaction->booking ? $transaction->booking->booking_ref : 'N/A';
        $this->sendToAdmins(
            'refund_processed',
            'Refund Processed',
            "Refund of {$transaction->currency} {$transaction->amount} processed for booking #{$bookingRef}",
            $transaction,
            [
                'transaction_id' => $transaction->id,
                'booking_id' => $transaction->booking_id,
                'action_url' => route('transaction.index')
            ],
            NotificationIcon::REFUND_PROCESSED,
            'transactions'
        );
    }

    /**
     * Notify when refund fails
     */
    public function notifyRefundFailed($transaction, string $reason = null): void
    {
        $customer = User::find($transaction->customer_id);
        if ($customer) {
            $message = $reason 
                ? "Refund of {$transaction->currency} {$transaction->amount} failed: {$reason}"
                : "Refund of {$transaction->currency} {$transaction->amount} failed";
            
            $this->send(
                $customer,
                'refund_failed',
                'Refund Failed',
                $message,
                'customer',
                $transaction,
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'booking_id' => $transaction->booking_id,
                    'failure_reason' => $reason,
                    'action_url' => "/transactions/{$transaction->id}"
                ],
                NotificationIcon::REFUND_FAILED,
                'transactions'
            );
        }

        // Notify admin
        $this->sendToAdmins(
            'refund_failed',
            'Refund Failed',
            "Refund failed for transaction #{$transaction->transaction_ref}",
            $transaction,
            [
                'transaction_id' => $transaction->id,
                'booking_id' => $transaction->booking_id,
                'failure_reason' => $reason,
                'action_url' => route('transaction.index')
            ],
            NotificationIcon::REFUND_FAILED,
            'transactions'
        );
    }

    /**
     * Notify provider when document is approved
     */
    public function notifyDocumentApproved(User $provider, string $documentType): void
    {
        $documentName = ucfirst(str_replace('_', ' ', $documentType));
        $this->send(
            $provider,
            'document_approved',
            'Document Approved',
            "Your {$documentName} has been approved",
            'provider',
            $provider,
            [
                'provider_id' => $provider->id,
                'document_type' => $documentType,
                'action_url' => route('user-management.service.providers.view', ['id' => $provider->id])
            ],
            NotificationIcon::DOCUMENT_VERIFICATION,
            'admin_actions'
        );
    }

    /**
     * Notify provider when document is rejected
     */
    public function notifyDocumentRejected(User $provider, string $documentType, string $reason = null): void
    {
        $documentName = ucfirst(str_replace('_', ' ', $documentType));
        $message = $reason 
            ? "Your {$documentName} has been rejected. Reason: {$reason}"
            : "Your {$documentName} has been rejected. Please upload a new document.";
        
        $this->send(
            $provider,
            'document_rejected',
            'Document Rejected',
            $message,
            'provider',
            $provider,
            [
                'provider_id' => $provider->id,
                'document_type' => $documentType,
                'rejection_reason' => $reason,
                'action_url' => route('user-management.service.providers.view', ['id' => $provider->id])
            ],
            NotificationIcon::WARNING,
            'admin_actions'
        );
    }

    /**
     * Notify provider when profile is completed
     */
    public function notifyProviderProfileCompleted(User $provider): void
    {
        $this->send(
            $provider,
            'provider_profile_completed',
            'Profile Completed',
            "Congratulations! Your provider profile has been completed and is now active",
            'provider',
            $provider,
            [
                'provider_id' => $provider->id,
                'action_url' => route('user-management.service.providers.view', ['id' => $provider->id])
            ],
            NotificationIcon::DOCUMENT_VERIFICATION,
            'admin_actions'
        );
    }

    /**
     * Notify when review is published
     */
    public function notifyReviewPublished($review): void
    {
        $provider = User::find($review->receiver_id);
        if ($provider) {
            $this->send(
                $provider,
                'review_published',
                'Review Published',
                "A new review has been published for your service",
                'provider',
                $review,
                [
                    'review_id' => $review->id,
                    'rating' => $review->rating,
                    'action_url' => "/provider/reviews/{$review->id}"
                ],
                NotificationIcon::REVIEW_RECEIVED,
                'reviews'
            );
        }
    }

    /**
     * Notify when review is unpublished
     */
    public function notifyReviewUnpublished($review): void
    {
        $provider = User::find($review->receiver_id);
        if ($provider) {
            $this->send(
                $provider,
                'review_unpublished',
                'Review Unpublished',
                "A review has been unpublished from your profile",
                'provider',
                $review,
                [
                    'review_id' => $review->id,
                    'action_url' => "/provider/reviews"
                ],
                NotificationIcon::REVIEW_PENDING,
                'reviews'
            );
        }
    }

    /**
     * Notify when dispute is resolved
     */
    public function notifyDisputeResolved($dispute, $booking = null): void
    {
        $customer = User::find($dispute->customer_id ?? $booking->customer_id ?? null);
        $provider = User::find($dispute->provider_id ?? $booking->provider_id ?? null);
        $bookingRef = $booking ? $booking->booking_ref : ($dispute->booking_ref ?? 'N/A');

        if ($customer) {
            $this->send(
                $customer,
                'dispute_resolved',
                'Dispute Resolved',
                "Your dispute for booking #{$bookingRef} has been resolved",
                'customer',
                $dispute,
                [
                    'dispute_id' => $dispute->id,
                    'booking_id' => $dispute->booking_id ?? null,
                    'action_url' => "/disputes/{$dispute->id}"
                ],
                NotificationIcon::ADMIN_ACTION,
                'admin_actions'
            );
        }

        if ($provider) {
            $this->send(
                $provider,
                'dispute_resolved',
                'Dispute Resolved',
                "Dispute for booking #{$bookingRef} has been resolved",
                'provider',
                $dispute,
                [
                    'dispute_id' => $dispute->id,
                    'booking_id' => $dispute->booking_id ?? null,
                    'action_url' => "/disputes/{$dispute->id}"
                ],
                NotificationIcon::ADMIN_ACTION,
                'admin_actions'
            );
        }

        // Notify admin
        $this->sendToAdmins(
            'dispute_resolved',
            'Dispute Resolved',
            "Dispute #{$dispute->id} for booking #{$bookingRef} has been resolved",
            $dispute,
            [
                'dispute_id' => $dispute->id,
                'booking_id' => $dispute->booking_id ?? null,
                'action_url' => route('dispute.index')
            ],
            NotificationIcon::ADMIN_ACTION,
            'admin_actions'
        );
    }
}
