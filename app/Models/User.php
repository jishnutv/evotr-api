<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'fname',
        'lname',
        'image',
        'vid',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'profile_completed'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function vid(): Attribute
    {
        return Attribute::make(
            set: fn($value) => 'VID' . $value,
        );
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->asDateTime($value)->diffForHumans(),
        );
    }
    protected function profileCompleted(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->fname) && !empty($this->lname) && !empty($this->phone),
        );
    }
}
