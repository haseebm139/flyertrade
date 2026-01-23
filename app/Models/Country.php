<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'iso2', 'phone_code', 'status', 'iso3', 'emoji', 'region', 'subregion'];

    public function getFlagUrlAttribute()
    {
        if (!$this->iso2) {
            return null;
        }

        return 'https://flagcdn.com/16x12/' . strtolower($this->iso2) . '.png';
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
