<?php

namespace App\Helpers;

/**
 * Notification Icon Constants
 * Icons are represented as string identifiers that can be mapped to actual icon components in frontend
 */
class NotificationIcon
{
    // Document/Verification Icons
    const DOCUMENT_VERIFICATION = 'document_verification'; // Green document with checkmark
    const DOCUMENT_PENDING = 'document_pending'; // Document icon
    
    // Booking Icons
    const BOOKING_CREATED = 'booking_created';
    const BOOKING_CONFIRMED = 'booking_confirmed';
    const BOOKING_CANCELLED = 'booking_cancelled'; // Documents with arrow down
    const BOOKING_COMPLETED = 'booking_completed';
    
    // Payment/Transaction Icons
    const PAYMENT_SUCCESS = 'payment_success';
    const PAYMENT_FAILED = 'payment_failed';
    const TRANSACTION = 'transaction';
    
    // Review Icons
    const REVIEW_RECEIVED = 'review_received';
    const REVIEW_PENDING = 'review_pending';
    
    // Alert Icons
    const HIGH_CANCELLATION_ALERT = 'high_cancellation_alert'; // Documents with arrow down
    const SYSTEM_ALERT = 'system_alert';
    const WARNING = 'warning';
    
    // Admin Action Icons
    const ADMIN_ACTION = 'admin_action';
    const ESCALATION = 'escalation';
    const DISPUTE = 'dispute'; // Flag icon for disputes
    
    // Provider Registration Icons
    const PROVIDER_REGISTERED = 'provider_registered'; // Multiple people icon
    
    // Message Icons
    const MESSAGE_RECEIVED = 'message_received';
    const NEW_MESSAGE = 'new_message'; // new_message
    // Promotion/Offer Icons
    const SPECIAL_OFFER = 'special_offer'; // Yellow percentage icon
    const PROMOTION = 'promotion'; // Yellow starburst with percentage
    
    // Service Icons
    const NEW_SERVICE = 'new_service'; // Green briefcase
    const JOB_COMPLETED = 'job_completed'; // Green face with checkmark
    
    // Reminder Icons
    const BOOKING_REMINDER = 'booking_reminder'; // Bell icon
    const REMINDER = 'reminder'; // Bell icon (generic)
    
    // Reschedule Icons
    const RESCHEDULE_REQUEST = 'reschedule_request'; // Calendar with arrow
    const RESCHEDULE_ACCEPTED = 'reschedule_accepted'; // Calendar with check
    const RESCHEDULE_REJECTED = 'reschedule_rejected'; // Calendar with X
    
    // Refund Icons
    const REFUND_PROCESSED = 'refund_processed'; // Money with arrow back
    const REFUND_FAILED = 'refund_failed'; // Money with X
    
    /**
     * Get icon for notification type
     */
    public static function getIconForType(string $type): string
    {
        return match($type) {
            'document_verification', 'document_pending' => self::DOCUMENT_VERIFICATION,
            'booking_created' => self::BOOKING_CREATED,
            'booking_confirmed' => self::BOOKING_CONFIRMED,
            'booking_cancelled' => self::BOOKING_CANCELLED,
            'booking_completed' => self::BOOKING_COMPLETED,
            'job_completed', 'service_completed' => self::JOB_COMPLETED,
            'payment_success', 'payment_successful' => self::PAYMENT_SUCCESS,
            'payment_failed' => self::PAYMENT_FAILED,
            'transaction_created', 'transaction_completed' => self::TRANSACTION,
            'review_received', 'review_pending' => self::REVIEW_RECEIVED,
            'high_cancellation_alert' => self::HIGH_CANCELLATION_ALERT,
            'provider_late_escalation' => self::ESCALATION,
            'dispute_created', 'new_dispute', 'dispute' => self::DISPUTE,
            'provider_registered', 'new_provider_registered' => self::PROVIDER_REGISTERED,
            'message_received' => self::MESSAGE_RECEIVED,
            'new_message' => self::NEW_MESSAGE,
            'special_offer', 'todays_offer' => self::SPECIAL_OFFER,
            'promotion', 'promotion_offer' => self::PROMOTION,
            'new_service', 'service_available' => self::NEW_SERVICE,
            'booking_reminder', 'reminder' => self::BOOKING_REMINDER,
            'reschedule_requested', 'booking_reschedule_request' => self::RESCHEDULE_REQUEST,
            'reschedule_accepted', 'booking_reschedule_accepted' => self::RESCHEDULE_ACCEPTED,
            'reschedule_rejected', 'booking_reschedule_rejected' => self::RESCHEDULE_REJECTED,
            'refund_processed', 'refund_success' => self::REFUND_PROCESSED,
            'refund_failed' => self::REFUND_FAILED,
            'booking_rejected' => self::BOOKING_CANCELLED, // Reuse cancelled icon
            'booking_started', 'booking_in_progress' => self::BOOKING_CONFIRMED, // Reuse confirmed icon
            'document_approved', 'verification_approved' => self::DOCUMENT_VERIFICATION,
            'document_rejected', 'verification_rejected' => self::WARNING,
            'review_published' => self::REVIEW_RECEIVED,
            'review_unpublished' => self::REVIEW_PENDING,
            'dispute_resolved', 'dispute_closed' => self::ADMIN_ACTION,
            default => self::SYSTEM_ALERT,
        };
    }
    
    /**
     * Get category for notification type
     */
    public static function getCategoryForType(string $type): string
    {
        return match($type) {
            'document_verification', 'document_pending', 'document_approved', 'verification_approved', 'document_rejected', 'verification_rejected', 'dispute_resolved', 'dispute_closed' => 'admin_actions',
            'booking_created', 'booking_confirmed', 'booking_cancelled', 'booking_completed', 'job_completed', 'service_completed', 'booking_reminder', 'reminder', 'booking_rejected', 'booking_started', 'booking_in_progress', 'reschedule_requested', 'booking_reschedule_request', 'reschedule_accepted', 'booking_reschedule_accepted', 'reschedule_rejected', 'booking_reschedule_rejected' => 'bookings',
            'payment_success', 'payment_successful', 'payment_failed', 'transaction_created', 'transaction_completed', 'refund_processed', 'refund_success', 'refund_failed' => 'transactions',
            'review_received', 'review_pending', 'review_published', 'review_unpublished' => 'reviews',
            'high_cancellation_alert', 'provider_late_escalation', 'admin_action', 'dispute_created', 'new_dispute', 'dispute' => 'admin_actions',
            'provider_registered', 'new_provider_registered' => 'admin_actions',
            'message_received' => 'messages',
            'new_message' => 'messages',
            'special_offer', 'todays_offer', 'promotion', 'promotion_offer' => 'promotions',
            'new_service', 'service_available' => 'services',
            default => 'all',
        };
    }
}
