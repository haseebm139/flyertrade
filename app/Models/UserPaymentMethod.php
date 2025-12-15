<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPaymentMethod extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_method_id',
        'brand',
        'last4',
        'exp_month',
        'exp_year',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

