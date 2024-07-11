<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use App\Models\User;

class ConversationController extends Controller
{
    public function create(Request $request)
    {
        $users = User::where('id', '!=', $request->user()->id)
                        ->select('id','name')
                        ->get()
                        ->mapWithKeys(function ($user) {
                            return [$user->id => $user->name];
                        })
                        ->toArray();

        $users = array_replace([0 => ''],$users);
        
        return view('chat.conversation',['formTitle' => 'Start a New conversation', 'users' => $users]);
    }

    public function store(StoreConversationRequest $request)
    {
        $validatedData = $request->validated();
    
        $conversation = $this->getConversations($validatedData['type'], $validatedData['participants']);
        
        if ($conversation->isNotEmpty()) {
            return redirect('/chats')->with(['status' => 'success', 'message' => 'Conversation already exists']);
        }

        \DB::beginTransaction();

        try {
            $conversation = Conversation::create([
                'type' => $validatedData['type'],
                'name' => $validatedData['name'] ?? null,
                'description' => $validatedData['description'] ?? null
            ]);

            $conversation->users()->attach($validatedData['participants']);

            if ($validatedData['type'] === 'group') {
                foreach ($validatedData['admin_ids'] as $admin_id) {
                    $conversation->users()->updateExistingPivot($admin_id, ['is_admin' => true]);
                }
            }

            \DB::commit();
            
            return redirect('/chats')->with(['status' => 'success', 'message' => 'Conversation created successfully']);

        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['status' => 'error', 'message' => 'Failed to start conversation.'], 500);
        }
    }

    private function getConversations($type, $participants)
    {        
        $query = \DB::table('conversations as c')
        ->select('c.*')
        ->where('c.type', $type);

        foreach ($participants as $index => $userId) {
        $alias = 'cu' . $index;
        $query->join("conversation_users as $alias", function($join) use ($alias, $userId) {
            $join->on("$alias.conversation_id", '=', 'c.id')
                ->where("$alias.user_id", '=', $userId);
        });
        }

        $conversations = $query->groupBy('c.id')->get();

        return $conversations ?? null;
    }
}
