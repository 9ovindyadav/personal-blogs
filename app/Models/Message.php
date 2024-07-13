<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = [
        'content',
        'content_type',
        'author_id',
        'author_name',
        'conversation_id',
        'send_at',
        'message_id',
        'status'
    ];
}
