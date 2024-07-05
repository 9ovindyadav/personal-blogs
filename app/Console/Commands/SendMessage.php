<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ElephantIO\Client;

use App\Events\MessageReceived;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:send {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send message to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
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
        $message = $this->argument('message');
        // MessageReceived::dispatch($message);

        $options = ['client' => Client::CLIENT_4X];
        
        $client = Client::create(env('WEBSOCKET_URL'), $options);
        $client->connect();
        $client->of('/');
        
        $data = ['message' => $message];
        $client->emit('chat-message', $data);
        
        $client->disconnect();
        $this->info('Message sent');
        return 0;
    }
}
