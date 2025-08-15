<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderServiceMedia extends Model
{
   protected $guarded = [];

   public function service()
    {
        return $this->belongsTo(ProviderService::class);
    }
}
