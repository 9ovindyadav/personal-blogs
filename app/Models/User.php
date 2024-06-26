<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}
