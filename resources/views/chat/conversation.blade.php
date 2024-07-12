<x-layout title="{{ isset($conversation) ? 'Edit: '.$task->subject : $formTitle }}">
<div class="flex min-h-full flex-col justify-center p-2 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ $formTitle }}</h2>
  </div>

  <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-3" action="/conversation/{{ isset($conversation) ? 'update' : 'store' }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($task))
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
        @endif

        <input type="hidden" name="participants[]" value="{{ auth()->user()->id }}">
        <input type="hidden" name="admin_ids[]" value="{{ auth()->user()->id }}">
        
        @include('components.form.select',
                [
                    'label' => 'Conversation type',
                    'name' => 'type',
                    'value' => ['private' => 'Private', 'group' => 'Group'],
                    'selected' => $conversation->type ?? 'private',
                    'attributes' => [
                                        'id' => 'conversation_type',
                                        'onchange' => 'showConversationInput(this)'
                                    ]
                ]
        )

        <div id="conversationFormContainer">
            @include('components.form.select',
                    [
                        'label' => 'Select User',
                        'name' => 'participants[]',
                        'value' => $users,
                        'selected' => $conversation->participants ?? null,
                        'attributes' => [
                                            'id' => 'assigned_to'
                                        ]
                    ]
            )

            
        </div>
        <div class="flex justify-around">
            <a href="/chats" class="flex w-[30%] justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancel
            </a>
            <button type="submit" class="flex w-[30%] justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
            </button>
        </div>
    </form>
  </div>
</div>
<script>
    const conversationFormContainer = document.getElementById('conversationFormContainer');

    function showConversationInput(input)
    {
        console.log(input.value);

        switch(input.value){
            case 'private':
                conversationFormContainer.innerHTML = `@include('components.form.select',
                                                    [
                                                        'label' => 'Select User',
                                                        'name' => 'participants[]',
                                                        'value' => $users,
                                                        'selected' => $conversation->participants ?? null,
                                                        'attributes' => [
                                                                            'id' => 'assigned_to'
                                                                        ]
                                                    ]
                                            )`;
                break;
            case 'group':
                conversationFormContainer.innerHTML = `@include('components.form.text',
                                                    [
                                                        'label' => 'Group Name',
                                                        'name' => 'name',
                                                        'value' => $conversation->name ?? null,
                                                        'attributes' => [
                                                                            'id' => 'conversation_name'
                                                                        ]
                                                    ]
                                            )

                                            @include('components.form.select',
                                                    [
                                                        'label' => 'Select Participants',
                                                        'name' => 'participants[]',
                                                        'value' => $users,
                                                        'selected' => $conversation->participants ?? null,
                                                        'attributes' => [
                                                                            'id' => 'assigned_to',
                                                                            'multiple' => true,
                                                                            'size' => 5
                                                                        ]
                                                    ]
                                            )`;
                break;
        }
    }
</script>
</x-layout>