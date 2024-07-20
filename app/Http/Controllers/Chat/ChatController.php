<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use ElephantIO\Client as WebSocketClient;
use App\Http\Controllers\Controller;
use App\Events\MessageReceived;
use App\Models\Message;
use App\Models\User;

use App\Jobs\UpdateMessageStatusJob;

class ChatController extends Controller
{
    public function index(Request $request)
    {   
        $user = $request->user();
        $conversations = $user->conversations()->get();
        
        return view('chat.index',['user' => $user,'conversations' => $conversations]);
    }

    public function sendMessage()
    {
        $attributes = request()->validate([
            'content' => ['required','max:100'],
            'content_type' => ['required','string'],
            'author_id' => ['int','required'],
            'author_name' => ['string'],
            'conversation_id' => ['string','required'],
            'send_at' => ['required'],
            'status' => ['required'],
            'message_id' => ['string','required']
        ]);

        $options = ['client' => WebSocketClient::CLIENT_4X];
        
        $client = WebSocketClient::create(env('WEBSOCKET_URL'), $options);
        $client->connect();
        $client->of('/');
        
        $data = ['channel' => "chat_{$attributes['conversation_id']}", 'message' => $attributes];
        $client->emit("pblogs-messages", $data);
        
        $client->disconnect();
        Message::create($attributes);

        return ['success' => true, 'message' => 'Message sent successfully'];
    }

    public function getMessages()
    {
        $attributes = request()->validate([
            'conversation_id' => ['string','required']
        ]);
        
        $messages = Message::where('conversation_id', $attributes['conversation_id'])->get();

        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function updateMessageStatus()
    {
        $attributes = request()->validate([
            'conversation_id' => 'required|string',
            'status' => 'required|string',
            'message_id' => '',
            'receiver_id' => 'required|int',
            'timestamp' => 'required'
        ]);

        UpdateMessageStatusJob::dispatch($attributes);

        return response()->json(['success' => true]);
    }

    public function updateUserLastSeen()
    {

    }
}
