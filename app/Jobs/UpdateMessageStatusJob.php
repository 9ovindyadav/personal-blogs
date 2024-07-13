<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Message;

class UpdateMessageStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected array $message)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $query = Message::where('conversation_id','=', $this->message['conversation_id']);
        switch($this->message['status']){
            case 'delivered':
            case 'seen':
                if(!empty($this->message['message_id'])){
                    $message = $query->where('message_id','=',$this->message['message_id'])->first();
                    if($message){
                        $message->status = $this->message['status'];
                        $message->save();
                    }
                }
                break;
            case 'all_seen':
                $query->where('status','!=','seen')->update(['status' => 'seen']);
                break;
        }
    }
}
