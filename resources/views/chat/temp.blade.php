<x-layout title="Chats">

<div class="flex antialiased text-gray-800" style="height: 90vh">
    <div class="flex flex-row h-full w-full overflow-x-hidden">
      @include('chat.sidebar',['user' => $user, 'friends' => $friends])

      @include('chat.chat')
    </div>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script>

        const socket = io("{{ env('WEBSOCKET_URL') }}");

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const chatContainerParent = document.getElementById('chatContainerParent');
        const chatContainer = document.getElementById('chatContainer');
        const messageInput = document.getElementById('messageInput');
        const messageSendBtn = document.getElementById('messageSendBtn');

        const senderId = "{{ auth()->user()->id }}";
        const senderName = "{{ auth()->user()->name }}";
        let receiverId;
        let receiverType = 'user';
        const receiver = {
            id: '',
            channel: '',
            type: 'user'
        };

        const friends = @json($friends);
        
        function setupListeners() {
            friends.forEach(friend => {
                
                const friendChannel = friend.type && friend.type === 'group' ? `message-from-${friend.id}-to-${senderId}` : `message-for-${friend.id}`;

                socket.on(friendChannel, function(message){
                    console.log(message);
                    const friendElement = document.getElementById(`friend-${friend.id}`);
                       
                    let newMessageCount = friendElement.dataset.newMessageCount + 1;
                    friendElement.innerHTML = `
                    <div class="ml-2 text-sm font-semibold">${friend.name}</div>
                    <span  class="flex items-center justify-center text-xs bg-red-400 h-4 w-4 rounded-full">${newMessageCount}</span>
                    `;
                });
            });
        }
        setupListeners();

        function selectFriend(element, friend)
        {
            const siblings = element.parentNode.children;
            for (let i = 0; i < siblings.length; i++) {
                siblings[i].classList.remove('bg-gray-300');
            }

            element.classList.add('bg-gray-300');
            chatContainer.innerHTML = '';
            
            const friendElement = document.getElementById(`friend-${friend.id}`);
                       
            friendElement.innerHTML = `
            <div class="ml-2 text-sm font-semibold">${friend.name}</div>
            `;

            receiver.id = friend.id;
            if(friend.type){
                receiver.type = friend.type;
            }
            getMessages(senderId, friend.id, receiver.type);

            receiver.channel = receiver.type === 'user' ? `message-from-${friend.id}-to-${senderId}` : `message-for-${friend.id}`;
            
            socket.on(receiver.channel, function(message){
                console.log(message);
                if(message.sender_id !== senderId){
                    appendMessage(message.message, true, (new Date()).toISOString(),message.sender_name);
                }
            });
        }

        async function getMessages(sender, receiver, receiverType = 'user')
        {
            const formData = new FormData();
            formData.append('sender_id', sender);
            formData.append('receiver_id',receiver);
            formData.append('receiver_type',receiverType);

            let res = await fetch('/messages',{
                method:'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            res = await res.json();
            console.log(res);
            if(res.success && res.data.length > 0){
                res.data.forEach((message) => {

                    if(message.sender_id == senderId) {
                        appendMessage(message.message, false, message.created_at);
                    }else{
                        appendMessage(message.message, true, message.created_at, message.sender_name);
                    } 
                });
            }else{
                chatContainer.innerHTML = '';
            }

        }

        async function sendMessage() 
        {
            if(messageInput.value){
                
                appendMessage(messageInput.value);
                console.log(receiverId);
                const formData = new FormData();
                formData.append('message',messageInput.value);
                formData.append('sender_id', senderId);
                formData.append('sender_name', senderName);
                formData.append('receiver_id',receiver.id);
                formData.append('receiver_type',receiver.type);
                formData.append('event', receiver.type === 'user' ? `message-from-${senderId}-to-${receiver.id}` : `message-for-${receiver.id}`);

                let res = await fetch('/message/send',{
                    method:'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                res = await res.json();
                messageInput.value = '';
            }
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

        function appendMessage(message, isReceiver = false, timestamp = (new Date()).toISOString(), name = 'You'){
            
            if(isReceiver){
                chatContainer.innerHTML += `<div class="col-start-1 col-end-8 p-3 rounded-lg">
                            <div class="flex flex-row items-center">
                                <div class="relative min-w-32 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                <div class="absolute top-0 left-2 text-red-500" style="font-size: 0.6rem">
                                    ${name}
                                </div>
                                <div class="absolute top-0 right-2 text-gray-500" style="font-size: 0.6rem">
                                    ${getTimeInAmPm(timestamp)}
                                </div>
                                <div class="mt-2">
                                    ${message}
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>`;
            }else{
                chatContainer.innerHTML += `<div class="col-start-6 col-end-13 p-3 rounded-lg">
                            <div class="flex items-center justify-start flex-row-reverse">
                                <div class="relative min-w-20 mr-3 text-sm text-right bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                <div class="absolute top-0 left-2 text-red-500" style="font-size: 0.6rem">
                                    ${name}
                                </div>
                                <div class="absolute top-0 right-2 text-gray-500" style="font-size: 0.6rem">
                                    ${getTimeInAmPm(timestamp)}
                                </div>
                                <div class="mt-2">
                                    ${message}
                                </div>
                                </div>
                            </div>
                        </div>`;
            }

            chatContainerParent.scrollTop = chatContainerParent.scrollHeight;
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