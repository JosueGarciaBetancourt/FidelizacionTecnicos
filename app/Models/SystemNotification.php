<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SystemNotification extends Model
{
    use HasFactory;
    
    protected $table = 'system_notifications';
    public $timestamps = true;

    protected $fillable = [
        'icon', 'title', 'tblToFilter', 'item', 'description', 'routeToReview', 'active'
    ];

    protected $appends = ['timeAgo'];
    
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans(); // "Hace 2 min"
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($systemNotification) {
            $systemNotification->created_at = Carbon::now()->addHours(5);
            $systemNotification->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($systemNotification) {
            $systemNotification->updated_at = Carbon::now()->addHours(5);
        });
    }
}