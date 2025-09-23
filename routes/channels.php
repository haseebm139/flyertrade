<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{bookingId}', function ($user, $bookingId) {
    $booking = \App\Models\Booking::find($bookingId);
    return $booking && ($user->id === $booking->customer_id || $user->id === $booking->provider_id);
});

Broadcast::channel('booking.{bookingId}', function ($user, $bookingId) {
    return \Gate::forUser($user)->allows('view', \App\Models\Booking::find($bookingId));
});

Broadcast::channel('provider.location.{providerId}', function ($user, $providerId) {
    return true; // or restrict to booking participants
});

// Chat & Offers channels
Broadcast::channel('private-conversation.{conversationId}', function ($user, $conversationId) {
    return \App\Models\Conversation::whereKey($conversationId)
        ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
        ->exists();
});

Broadcast::channel('private-user.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
