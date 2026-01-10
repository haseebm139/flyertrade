<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\User;
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
        array $data = []
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
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
        array $data = []
    ): int {
        $notifications = [];
        $notifiableType = $notifiable ? get_class($notifiable) : null;
        $notifiableId = $notifiable ? $notifiable->id : null;

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
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
        array $data = []
    ): int {
        $adminIds = User::where('role_id', 'admin')
            ->orWhere('user_type', 'admin')
            ->pluck('id')
            ->toArray();

        if (empty($adminIds)) {
            return 0;
        }

        return $this->sendToMany($adminIds, $type, $title, $message, 'admin', $notifiable, $data);
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
}
