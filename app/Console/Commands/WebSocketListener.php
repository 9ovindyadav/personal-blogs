<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\Connector as RatchetConnector;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Connector as ReactConnector;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

use App\Services\InMemoryMessageStore;

class WebSocketListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connects to a WebSocket server and listens for messages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private InMemoryMessageStore $messageStore)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $loop = LoopFactory::create();
        $reactConnector = new ReactConnector($loop);
        $connector = new RatchetConnector($loop, $reactConnector);
        
        $connector('ws://localhost:8080/ws?client_id=php_server')->then(function ($conn) {
            $this->info("Connected to WebSocket server");       

            $conversations = Conversation::select('id')->get();
            $users = User::select('username')->get();

            $channels = $conversations->map(function ($conversation){
                return "conversation_{$conversation->id}";
            })->toArray();

            $userStatusChannels = $users->map(function ($user){
                return "user_{$user->username}_status";
            })->toArray();

            $channels = array_merge($channels ,$userStatusChannels);
          
            $joinMessage = [
                'type' => 'join',
                'channels' => $channels
            ];
            $conn->send(json_encode($joinMessage));

            $conn->on('message', function ($msg) {
                $msg = json_decode($msg);
                $msg = json_decode($msg->data, true);

                $this->info(json_encode($msg));

                $this->messageStore->addMessage($msg);
            });

            $conn->on('close', function ($code = null, $reason = null) {
                $this->info("Connection closed ({$code} - {$reason})");
            });

        }, function ($e) use ($loop) {
            $this->error("Could not connect: {$e->getMessage()}");
            $loop->stop();
        });

        $loop->run();

        return 0;
    }
}