<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($setting) {
            $setting->created_at = Carbon::now()->subHours(5);
            $setting->updated_at = Carbon::now()->subHours(5);
        });

        static::updating(function ($setting) {
            $setting->updated_at = Carbon::now()->subHours(5);
        });
    }
}
