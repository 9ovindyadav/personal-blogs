<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['type','name','discription'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            if(empty($model->{$model->getKeyName()})){
                $model->{$model->getKeyName()} = (string) \Str::uuid();
            }
        });
    }

    public function messages()
    {
        return $this->hasMany(Message::class,'conversation_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'conversation_users',)
                    ->withPivot('is_admin')
                    ->withTimestamps();
    }

    public function admins()
    {
        return $this->users()->wherePivot('is_admin', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('type','private');
    }

    public function scopeGroup($query)
    {
        return $query->where('type','group');
    }
}
