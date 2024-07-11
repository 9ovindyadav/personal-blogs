<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected function boot()
    {
        parent::boot();

        static::creating(function($model){
            if(empty($model->{$model->getKeyName()})){
                $model->{$model->getKeyName()} = (string) \Str::uuid();
            }
        });
    }
}
