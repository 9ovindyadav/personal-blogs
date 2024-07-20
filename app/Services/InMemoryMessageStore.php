<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class InMemoryMessageStore
{
    protected $cacheKey = 'in_memory_messages';

    public function __construct()
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, []);
        }
    }

    public function addMessage($message)
    {
        $messages = Cache::get($this->cacheKey);

        switch($message['message_type']){
            case 'conversation_message':
                $messages[$message['message_type']][$message['message_id']] = $message;
                break;
            case 'read_receipt':
                $messages[$message['message_type']][$message['conversation_id']] = $message;
                break;
            case 'user_status':
                $messages[$message['message_type']][$message['username']] = $message;
                break;
        }

        Cache::put($this->cacheKey, $messages, 300);
    }

    public function getMessages()
    {
        return Cache::get($this->cacheKey);
    }

    public function clearMessages()
    {
        Cache::put($this->cacheKey, []);
    }
}