<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\InMemoryMessageStore;
use App\Models\Message;

class ProcessInMemoryMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private InMemoryMessageStore $messageStore)
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messages = $this->messageStore->getMessages();
        $this->messageStore->clearMessages();

        
        if(isset($messages['conversation_message'])){
            $conversationMessages = $messages['conversation_message'];

            foreach($conversationMessages as &$message){
                unset($message['message_type']);
                if(isset($messages['read_receipt']) && isset($messages['read_receipt'][$message['message_id']])){
                    $message['status'] = $messages['read_receipt'][$message['message_id']]['status'];
                }
            }

            $conversationMessages = array_values($conversationMessages);

            if (!empty($conversationMessages)) {
                Message::insert($conversationMessages);
                \Log::info('Messages inserted into Database successfully');
            } else {
                \Log::error('Conversation messages are not properly formatted as a list of arrays');
            }
        }

        if(isset($messages['user_status'])){
            $usersLastSeen = $messages['user_status'];
        }
    }
}
