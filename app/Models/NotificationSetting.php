<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'new_orders',
        'offers',
        'promotions',
        'reminders',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
