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
}