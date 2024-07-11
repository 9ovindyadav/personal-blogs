<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

use Lab404\Impersonate\Models\Impersonate;

use App\Traits\DynamicRelationship;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Impersonate, DynamicRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class,'author_id');
    }

    public function canImpersonate()
    {
        return $this->is_admin;
    }

    public function canBeImpersonated()
    {
        return !$this->is_admin;
    }

    public function getProfileImgAttribute($value)
    {
        if(is_null($value) || $value = ''){
            return 'images/user-icon.jpeg';
        }

        return $value;
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->setTimezone(config('app.timezone'))->toIso8601String();
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class,'conversation_users')
                    ->withPivot('is_admin')
                    ->withTimestamps();
    }

    public function adminGroups()
    {
        $this->conversations()->wherePivot('is_admin', true);
    }

    public function messages()
    {
        return $this->hasMany(Message::class,'author_id');
    }
}
