<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\User;
use App\Helpers\NotificationIcon;
use Illuminate\Support\Facades\DB;

class NotificationService
{
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
        return Notification::create([
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
        $notifications = [];
        $notifiableType = $notifiable ? get_class($notifiable) : null;
        $notifiableId = $notifiable ? $notifiable->id : null;
        $icon = $icon ?? NotificationIcon::getIconForType($type);
        $category = $category ?? NotificationIcon::getCategoryForType($type);

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'icon' => $icon,
                'category' => $category,
                'title' => $title,
                'message' => $message,
                'recipient_type' => $recipientType,
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
                'data' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return Notification::insert($notifications);
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
        $adminIds = User::where('role_id', 'admin')
            ->orWhere('user_type', 'admin')
            ->pluck('id')
            ->toArray();

        if (empty($adminIds)) {
            return 0;
        }

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
            ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
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
            ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
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
    public function notifyDocumentVerificationPending(int $count = 0): int
    {
        return $this->sendToAdmins(
            'document_verification',
            'Document verification',
            $count > 0 ? "{$count} New Providers Awaiting Document Verification." : "New Providers Awaiting Document Verification.",
            null,
            ['count' => $count, 'action_url' => '/admin/providers/pending-verification'],
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
                'action_url' => "/admin/providers/{$providerId}"
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
                'action_url' => "/admin/bookings/{$booking->id}"
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
                'action_url' => "/admin/bookings/{$booking->id}"
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
                'action_url' => "/admin/providers/{$provider->id}"
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
                'action_url' => "/admin/disputes/{$dispute->id}"
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
                'action_url' => "/admin/reviews/{$review->id}"
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
}
