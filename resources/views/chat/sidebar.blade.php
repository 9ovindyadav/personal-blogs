<div class="flex flex-col py-8 pl-6 pr-2 w-64 bg-white flex-shrink-0">
    <a href="/conversation/create" class="flex flex-row items-center justify-center h-10 rounded-2xl bg-gray-300 w-full">
        <div class="flex items-center justify-center rounded-2xl text-indigo-700 bg-indigo-100 h-6 w-6">
        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
            ></path>
        </svg>
        </div>
        <div class="ml-2 font-bold text-sm">New Conversation</div>
    </a>

    <div class="flex flex-col mt-8">
        <div class="flex flex-row items-center justify-between text-xs">
        <span class="font-bold">Active Conversations</span>
        <span class="flex items-center justify-center bg-green-300 h-6 w-6 text-gray  rounded-full">
            {{ $conversations->count() }}
        </span
        >
        </div>
        <div class="flex flex-col space-y-1 mt-4 -mx-2 h-48 overflow-y-auto">
            @foreach($conversations as $conversation)
                <button
                    onclick="selectConversation(this,'{{ $conversation->id }}')"
                    class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2">
                    @php
                        $name;
                        if($conversation->type === 'private'){
                            $otherUser = $conversation->users->reject(function ($otherUser) use ($user) {
                                return $otherUser->id === $user->id;
                            })->first();
                            $name = $otherUser->name;
                        }else{
                            $name = $conversation->name;
                        }
                    @endphp
                    <div class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full">
                    {{ $name[0] }}
                    </div>
                    <div class="flex justify-between items-center w-full">
                        <div class="ml-2 text-sm font-semibold">{{ $name }}</div>
                        <span
                            data-new-message-count="0"
                            id="conversation-{{ $conversation->id }}"  
                            class="flex items-center justify-center text-xs bg-red-400 h-4 w-4 rounded-full hidden"></span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>
    
</div>