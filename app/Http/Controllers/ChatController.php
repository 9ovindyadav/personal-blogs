<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ElephantIO\Client as WebSocketClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

use App\Events\MessageReceived;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        \Log::info('Testing class autoloading');
        \Cache::put('testing','Hello Govind');
        
        $user = auth()->user();
        $newGroup = new User();
        $newGroup->name = 'Developers Group';
        $newGroup->id = '10';
        $newGroup->type = 'group';

        $friends = User::where('id','!=', $user->id)->get();
        $friends->prepend($newGroup);
        return view('chat.index',['user' => $user,'friends' => $friends]);
    }

    public function messageSend()
    {
        $attributes = request()->validate([
            'event' => ['max:20','required'],
            'message' => ['max:100','required'],
            'sender_id' => ['int','required'],
            'sender_name' => ['string'],
            'receiver_id' => ['int','required'],
            'receiver_type' => ['string','required']
        ]);

        $options = ['client' => WebSocketClient::CLIENT_4X];
        
        $client = WebSocketClient::create(env('WEBSOCKET_URL'), $options);
        $client->connect();
        $client->of('/');
        
        $data = ['event' => $attributes['event'], 'message' => $attributes];
        $client->emit('client-message', $data);
        
        $client->disconnect();
        // MessageReceived::dispatch($attributes['message'], $attributes['sender_id'], $attributes['receiver_id']);
        unset($attributes['event'],$attributes['sender_name']);
        Message::create($attributes);

        return ['success' => true, 'message' => 'Message sent successfully'];
    }

    public function getMessages()
    {
        $attributes = request()->validate([
            'sender_id' => ['int','required'],
            'receiver_id' => ['int','required'],
            'receiver_type' => ['string','required']
        ]);

        $senderId = $attributes['sender_id'];
        $receiverId = $attributes['receiver_id'];
        
        $query;
        if($attributes['receiver_type'] == 'group'){
            $query = Message::select('messages.*','users.name as sender_name')
                            ->where('receiver_id', $attributes['receiver_id'])
                            ->leftJoin('users','messages.sender_id','=','users.id');
        }else{
            $query = Message::select('messages.*','users.name as sender_name')
            ->where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                      ->where('receiver_id', $receiverId);
            })->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                      ->where('receiver_id', $senderId);
            })->leftJoin('users','messages.sender_id','=','users.id');
        }

        $messages = $query->orderBy('created_at')->get();
    
        return response()->json(['success' => true, 'data' => $messages]);
    }
}
