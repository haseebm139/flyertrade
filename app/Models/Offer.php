<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'customer_id',
        'provider_id',
        'service_type',
        'time_from',
        'time_to',
        'description',
        'status',
        'current_revision_id',
        'responded_at',
        'finalized_at',
    ];

    protected $casts = [
        'time_from' => 'datetime',
        'time_to' => 'datetime',
        'responded_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(OfferRevision::class);
    }
}

