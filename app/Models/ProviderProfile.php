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
        'user_id',
        'city_id',
        'business_name',
        'category_id',
        'phone',
        'bio',
        'description',
        'cover_image',
        'status',
        'is_featured',
        'rating',
        'working_hours',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
