<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EmailLog extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'recipient_email',
        'recipient_type',
        'subject',
        'body',
        'status',
        'error_message',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function getRecipientImageAttribute()
    {
        return $this->recipient?->avatar;
    }

    public function getRecipientNameAttribute()
    {
        return $this->recipient?->name;
    }

    public function getRecipientEmailAttribute($value)
    {
        return $this->recipient?->email ?? $value;
    }
}
