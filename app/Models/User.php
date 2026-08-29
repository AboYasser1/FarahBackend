<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'status',
        'avatar',
        'bio',
        'cover_image',
        'last_login_at',
        'is_online',
        'city_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',

    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function providerProfile()
    {
        return $this->hasOne(ProviderProfile::class, 'user_id');
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class, 'user_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isProvider()
    {
        return $this->user_type === 'provider';
    }
}
