<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'category',
        'title',
        'message',
        'recipient_type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent notifiable model (booking, transaction, etc.)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope by recipient type
     */
    public function scopeForRecipient($query, string $type)
    {
        return $query->where('recipient_type', $type)->orWhere('recipient_type', 'all');
    }

    /**
     * Get icon URL for the notification
     * Returns the full URL path to the icon file
     */
    public function getIconUrlAttribute(): string
    {
        $iconPath = $this->getIconPath($this->icon ?? 'system_alert');
        return asset($iconPath);
    }

    /**
     * Get icon path based on icon identifier
     */
    private function getIconPath(string $icon): string
    {
        $iconMap = [
            'document_verification' => 'assets/images/icons/manage.svg',
            'document_pending' => 'assets/images/icons/manage.svg',
            'booking_created' => 'assets/images/icons/manage.svg',
            'booking_confirmed' => 'assets/images/icons/manage.svg',
            'booking_cancelled' => 'assets/images/icons/manage.svg',
            'booking_completed' => 'assets/images/icons/manage.svg',
            'job_completed' => 'assets/images/icons/manage.svg',
            'payment_success' => 'assets/images/icons/manage.svg',
            'payment_failed' => 'assets/images/icons/notification-payment-failed.svg',
            'transaction' => 'assets/images/icons/manage.svg',
            'review_received' => 'assets/images/icons/manage.svg',
            'review_pending' => 'assets/images/icons/manage.svg',
            'dispute' => 'assets/images/icons/manage.svg',
            'provider_registered' => 'assets/images/icons/manage.svg',
            'high_cancellation_alert' => 'assets/images/icons/manage.svg',
            'system_alert' => 'assets/images/icons/manage.svg',
            'warning' => 'assets/images/icons/manage.svg',
            'admin_action' => 'assets/images/icons/manage.svg',
            'escalation' => 'assets/images/icons/manage.svg',
            'message_received' => 'assets/images/icons/manage.svg',
            'special_offer' => 'assets/images/icons/manage.svg',
            'promotion' => 'assets/images/icons/manage.svg',
            'new_service' => 'assets/images/icons/manage.svg',
            'booking_reminder' => 'assets/images/icons/manage.svg',
            'reminder' => 'assets/images/icons/manage.svg',
            'reschedule_request' => 'assets/images/icons/manage.svg',
            'reschedule_accepted' => 'assets/images/icons/manage.svg',
            'reschedule_rejected' => 'assets/images/icons/manage.svg',
            'refund_processed' => 'assets/images/icons/manage.svg',
            'refund_failed' => 'assets/images/icons/manage.svg',
        ];
        // $iconMap = [
        //     'document_verification' => 'assets/images/icons/notification-document-verification.svg',
        //     'document_pending' => 'assets/images/icons/notification-document-pending.svg',
        //     'booking_created' => 'assets/images/icons/notification-booking-created.svg',
        //     'booking_confirmed' => 'assets/images/icons/notification-booking-confirmed.svg',
        //     'booking_cancelled' => 'assets/images/icons/notification-booking-cancelled.svg',
        //     'booking_completed' => 'assets/images/icons/notification-booking-completed.svg',
        //     'job_completed' => 'assets/images/icons/notification-job-completed.svg',
        //     'payment_success' => 'assets/images/icons/notification-payment-success.svg',
        //     'payment_failed' => 'assets/images/icons/notification-payment-failed.svg',
        //     'transaction' => 'assets/images/icons/notification-transaction.svg',
        //     'review_received' => 'assets/images/icons/notification-review-received.svg',
        //     'review_pending' => 'assets/images/icons/notification-review-pending.svg',
        //     'dispute' => 'assets/images/icons/notification-dispute.svg',
        //     'provider_registered' => 'assets/images/icons/notification-provider-registered.svg',
        //     'high_cancellation_alert' => 'assets/images/icons/notification-cancellation-alert.svg',
        //     'system_alert' => 'assets/images/icons/notification-system-alert.svg',
        //     'warning' => 'assets/images/icons/notification-warning.svg',
        //     'admin_action' => 'assets/images/icons/notification-admin-action.svg',
        //     'escalation' => 'assets/images/icons/notification-escalation.svg',
        //     'message_received' => 'assets/images/icons/notification-message.svg',
        //     'special_offer' => 'assets/images/icons/notification-special-offer.svg',
        //     'promotion' => 'assets/images/icons/notification-promotion.svg',
        //     'new_service' => 'assets/images/icons/notification-new-service.svg',
        //     'booking_reminder' => 'assets/images/icons/notification-reminder.svg',
        //     'reminder' => 'assets/images/icons/notification-reminder.svg',
        //     'reschedule_request' => 'assets/images/icons/notification-reschedule-request.svg',
        //     'reschedule_accepted' => 'assets/images/icons/notification-reschedule-accepted.svg',
        //     'reschedule_rejected' => 'assets/images/icons/notification-reschedule-rejected.svg',
        //     'refund_processed' => 'assets/images/icons/notification-refund-processed.svg',
        //     'refund_failed' => 'assets/images/icons/notification-refund-failed.svg',
        // ];

        return $iconMap[$icon] ?? $iconMap['system_alert'];
    }

    /**
     * Get icon component name (for React/Vue components)
     */
    public function getIconComponentAttribute(): string
    {
        // Convert snake_case to PascalCase for component names
        $parts = explode('_', $this->icon ?? 'system_alert');
        $component = implode('', array_map('ucfirst', $parts));
        return $component . 'Icon';
    }
}
