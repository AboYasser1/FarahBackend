<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderProfileFactory> */
    use HasFactory;


    protected $table = 'provider_profiles';

    protected $fillable = [
        'business_name',
        'category',
        'bio',
        'users_id',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
