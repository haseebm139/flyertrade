<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'by_user_id',
        'cost_items',
        'materials',
        'flat_fee',
        'currency',
        'subtotal',
        'tax',
        'total',
        'notes',
    ];

    protected $casts = [
        'cost_items' => 'array',
        'materials' => 'array',
        'flat_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'by_user_id');
    }
}

