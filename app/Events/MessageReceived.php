<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tries = 1;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public string $message,
        public int $senderId,
        public int $receiverId,
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['message-received'];
    }

    public function broadcastAs()
    {
        return "message-{$this->receiverId}-{$this->senderId}";
    }

    public function failed(Exception $exception)
    {
        Log::error('Broadcasting failed: ' . $exception->getMessage());
    }
}
