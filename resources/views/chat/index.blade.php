<x-layout title="Chats">

<div class="flex antialiased text-gray-800" style="height: 90vh">
    <div class="flex flex-row h-full w-full overflow-x-hidden">
      @include('chat.sidebar',['user' => $user, 'conversations' => $conversations])

        <div class="flex flex-col flex-auto h-full p-2" id="conversationContainer">
            <div class="rounded-2xl flex justify-center items-center h-full bg-gray-100 p-2">
                <h2 class="text-2xl">Select Conversation</h2>
            </div>
        </div>
    </div>
    <script>

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const currentUser = @json($user);
        const conversations = @json($conversations);
        
        const conversationContainer = document.getElementById('conversationContainer');
        let messageInput;
        let chatContainerParent;
        let chatContainer;

        let currentConversation = null;

        const webSocket = new WebSocket(`ws://localhost:8080/ws?client_id=${currentUser.id}`);
        
        function connect() {
            webSocket.onopen = () => {
                console.log('Connected to the WebSocket server');
                
                const channels = [];

                conversations.forEach((conversation)=>{
                    channels.push(`conversation_${conversation.id}`);
                });
                
                // Send a message to join multiple channels
                const joinMessage = {
                    type: 'join',
                    channels
                };
                webSocket.send(JSON.stringify(joinMessage));

                sendUserStatus();
                setInterval(sendUserStatus, 5000);
            };

            let conversationStatusTimer;

            webSocket.onmessage = (event) => {
                const data = JSON.parse(event.data);
                const message = JSON.parse(data.data);
            
                console.log(message);
                switch(message.message_type){
                    case 'conversation_message':

                        if(message.author_username != currentUser.username){
                            const conversationElement = document.getElementById(`conversation-${message.conversation_id}`);
                            conversationElement.textContent = conversationElement.dataset.newMessageCount + 1;
                            conversationElement.classList.remove('hidden');

                            let messageStatus = 'delivered';
                            if(currentConversation.id == message.conversation_id){
                                appendMessage(message);
                                messageStatus = 'seen';
                            }

                            sendToChannel(`conversation_${message.conversation_id}`, {
                                message_type: 'read_receipt',
                                conversation_id: message.conversation_id,
                                message_id: message.message_id,
                                receiver_username: currentUser.username,
                                status: messageStatus,
                                timestamp: (new Date()).toISOString()
                            });
                        }
                        break;

                    case 'read_receipt':

                        if(message.receiver_username != currentUser.username){
                            if(message.status == 'all_seen'){
                                const messageStatusElements = document.querySelectorAll(`[data-id="conversation_${message.conversation_id}_message_status"]`);
                            
                                messageStatusElements.forEach((messageStatusElement) =>{
                                    messageStatusElement.classList.remove('fa-regular','text-gray-400');
                                    messageStatusElement.classList.add('fa-solid','text-green-400');
                                });
                                return;
                            }

                            let messageStatusElement = document.getElementById(`message_${message.message_id}_status`);
                            
                            switch(message.status){
                                case 'delivered':
                                    messageStatusElement.classList.remove('fa-regular');
                                    messageStatusElement.classList.add('fa-solid','text-gray-400');
                                    break;
                                case 'seen':
                                    messageStatusElement.classList.remove('fa-regular','text-gray-400');
                                    messageStatusElement.classList.add('fa-solid','text-green-400');
                                    break;
                            }
                        }
                        break;
                    case 'user_status':
                        
                        if(message.username == currentConversation.username){
                            const  conversationStatusElement = document.getElementById('conversationStatus');
                            conversationStatusElement.innerHTML = '<span class="bg-green-100 text-green-800 me-2 px-2 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300" style="font-size: 9px">Online</span>';

                            if(conversationStatusTimer){
                                clearTimeout(conversationStatusTimer);
                            }

                            conversationStatusTimer = setTimeout(()=>{
                                conversationStatusElement.innerHTML = '<span class="bg-gray-100 text-gray-800 me-2 px-2 py-0.5 rounded-full dark:bg-gray-900 dark:text-gray-300" style="font-size: 9px">Offline</span>';
                            },6000);
                        }

                        break;
                }
            };

            webSocket.onclose = () => {
                console.log('Disconnected from the WebSocket server');
            };

            webSocket.onerror = (error) => {
                console.error('WebSocket error:', error);
            };
        }

        connect();

        // function setupListeners() {
        //     conversations.forEach(conversation => {
                
        //         socket.on(`chat_${conversation.id}`, function(message){
        //             console.log(message);
        //             

        //         });

        //         socket.on(`conversation_${conversation.id}_message_status`, async (message) =>{
                    
        //            
        //         });
        //     });

        // }

        // setupListeners();

        function sendToChannel(channel, message){

            webSocket.send(JSON.stringify({channel_id: channel, data: JSON.stringify(message)}));
        
        }

        function sendUserStatus()
        {
            sendToChannel(
                    `user_${currentUser.username}_status`, 
                    {
                        message_type: 'user_status',
                        username: currentUser.username,
                        status: 1,
                        lastSeen: (new Date()).toISOString()
                    }
                );
        }

        async function selectConversation(element, conversation)
        {
            conversationContainer.innerHTML = await conversationContainerHtml(conversation);
            chatContainerParent = document.getElementById('chatContainerParent');
            chatContainer = document.getElementById('chatContainerParent');
            messageInput = document.getElementById('messageInput');
           
            const messageSendBtn = document.getElementById('messageSendBtn');

            if(conversation.type == 'private'){

                const joinMessage = {
                    type: 'join',
                    channels: [`user_${conversation.username}_status`]
                };
                webSocket.send(JSON.stringify(joinMessage));
            }

            messageSendBtn.addEventListener('click', function(e) {
                e.preventDefault();
                sendMessage();
            });

            messageInput.addEventListener('keydown', function(e) {
                if(e.key === 'Enter'){
                    e.preventDefault();
                    sendMessage();
                }
            });

            const siblings = element.parentNode.children;
            for (let i = 0; i < siblings.length; i++) {
                siblings[i].classList.remove('bg-gray-300');
            }

            element.classList.add('bg-gray-300');
            chatContainer.innerHTML = '';
           
            currentConversation = conversation;
            await getMessages(conversation.id);
            const conversationElement = document.getElementById(`conversation-${conversation.id}`);
            conversationElement.dataset.newMessageCount = 0;
            conversationElement.classList.add('hidden');
        
            sendToChannel(`conversation_${currentConversation.id}`, {
                message_type: 'read_receipt',
                conversation_id: currentConversation.id,
                receiver_username: currentUser.username,
                status: 'all_seen',
                timestamp: (new Date()).toISOString()
            });
        }

        async function getMessages(conversationId)
        {
            const formData = new FormData();
            formData.append('conversation_id', conversationId);

            let res = await fetch('/messages',{
                method:'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            res = await res.json();
           
            if(res.success && res.data.length > 0){
                res.data.forEach((message) => {
                    appendMessage(message);
                });
            }else{
                chatContainer.innerHTML = '';
            }

        }

        async function sendMessage() 
        {
            if(messageInput.value && currentConversation){
                
                const message = {
                    message_type: 'conversation_message',
                    content: messageInput.value, 
                    content_type: 'text',
                    author_username: currentUser.username,
                    author_name: currentUser.name,
                    conversation_id: currentConversation.id,
                    send_at: (new Date()).toISOString(),
                    message_id: `${currentConversation.id}_${currentUser.username}_${Date.now()}`,
                    status: 'sent'
                };

                appendMessage(message);
                
                sendToChannel( `conversation_${currentConversation.id}`, message);

                messageInput.value = '';
        
            }
        }

        function appendMessage(message){
            const isCurrentUser = message.author_username == currentUser.username ;
            let messageStatusClass;
            switch(message.status){
                case 'sent':
                    messageStatusClass = 'fa-regular';
                    break;
                case 'delivered':
                    messageStatusClass = 'fa-regular text-gray-400';
                    break;
                case 'seen':
                    messageStatusClass = 'fa-solid text-green-400'
                    break;
            }

            chatContainer.innerHTML += `<div class="col-start-1 col-end-8 p-3 rounded-lg">
                            <div class="${ !isCurrentUser ? 'flex flex-row items-center' : 'flex items-center justify-start flex-row-reverse'}">
                                <div class="relative min-w-32 text-sm ${ !isCurrentUser ? 'bg-white py-2 px-4 shadow rounded-xl' : 'mr-3 bg-indigo-100 py-2 px-4 shadow rounded-xl'}"
                                    id="${message.message_id}">
                                    <div class="absolute top-0 left-2 text-red-500" style="font-size: 0.6rem">
                                        ${message.author_name}
                                    </div>
                                    <div class="absolute top-0 right-2 text-gray-500" style="font-size: 0.6rem">
                                        ${getTimeInAmPm(message.send_at)}
                                    </div>
                                    <div class="mt-2">
                                        ${message.content}
                                    </div>
                                    ${ isCurrentUser ? `<i data-id="conversation_${message.conversation_id}_message_status" id="message_${message.message_id}_status" class="${messageStatusClass} fa-circle-check absolute bottom-1 right-1" style="font-size: 10px"></i>`:''}
                                </div>
                            </div>
                            </div>
                        </div>`;

            chatContainerParent.scrollTop = chatContainerParent.scrollHeight;
        }

        async function conversationContainerHtml(conversation)
        {
            return `
                <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-2">
                    <div class="flex flex-row items-center h-16 rounded-xl bg-gray-200 w-full px-4">
                        <div class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full">
                            ${conversation.name[0]}
                        </div>
                        <div class="w-full ml-2">
                            <div class="text-md font-semibold">${conversation.name}</div>
                            <div class="m-0 p-0" style="font-size: 10px" id="conversationStatus"></div>
                        </div>
                    </div>
                    <div class="flex flex-col h-full overflow-x-auto mb-4" id="chatContainerParent">
                        <div class="flex flex-col h-full">
                            <div class="grid grid-cols-12 gap-y-2" id="chatContainer">

                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">
                        <div>
                            <button class="flex items-center justify-center text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-grow ml-4">
                            <div class="relative w-full">
                                <input id="messageInput" type="text"
                                    class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" />
                                <button
                                    class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="ml-4">
                            <button id="messageSendBtn"
                                class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 pt-2 pb-3 flex-shrink-0">
                                <!-- <span>Send</span> -->
                                <span class="">
                                    <svg class="w-6 h-6 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function getTimeInAmPm(isoTimestamp)
        {
            const date = new Date(isoTimestamp);
            
            let hours = date.getHours();
            const minutes = date.getMinutes();
            const amPm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12; // convert hours to 12 hour format
            hours = hours ? hours : 12 ; // the hour 0 should be 12

            const formatedMinutes = minutes < 10 ? '0'+minutes : minutes ;
            
            const timeString = `${hours}:${formatedMinutes} ${amPm}`;
            return timeString;
        }
    </script>

</x-layout>