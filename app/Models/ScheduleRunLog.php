<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleRunLog extends Model
{
    protected $fillable = [
        'ran_at',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'ran_at' => 'datetime',
        ];
    }
}
