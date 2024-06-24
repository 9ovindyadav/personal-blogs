<x-layout title="{{ isset($task) ? 'Edit: '.$task->subject : 'Create New Task' }}">
<div class="flex min-h-full flex-col justify-center p-2 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ $formTitle }}</h2>
  </div>

  <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-3" action="/task/{{ isset($task) ? 'update' : 'store' }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($task))
            <input type="hidden" name="task_id" value="{{ $task->id }}">
        @endif

        @include('components.form.text',
                [
                    'label' => 'Subject',
                    'name' => 'subject',
                    'value' => $task->subject ?? null,
                    'attributes' => [
                                        'id' => 'subject'
                                    ]
                ]
        )

        @include('components.form.select',
                [
                    'label' => 'Assigned To',
                    'name' => 'assigned_to',
                    'value' => $users,
                    'selected' => $task->assigned_to ?? null,
                    'attributes' => [
                                        'id' => 'assigned_to',
                                        'onchange' => 'getUserProjects(this)'
                                    ]
                ]
        )

        @include('components.form.select',
                [
                    'label' => 'Project',
                    'name' => 'project_id',
                    'value' => isset($task) ? [$task->relation('project','M:M',true)->first()->id => $task->relation('project','M:M',true)->first()->name ] : [],
                    'selected' => $task->project_id ?? null,
                    'attributes' => [
                                        'id' => 'user_projects'
                                    ]
                ]
        )
        
        <div class="flex justify-around">
            <a href="/tasks" class="flex w-[30%] justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
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
    async function getUserProjects(input)
    {
        const formData = new FormData();
        formData.append('user_id',input.value);
        formData.append('_token',"{{ csrf_token() }}");
        const url = `/user/${input.value}/projects`;
        let res = await fetch(url,{
            method: 'POST',
            body: formData
        })

        res = await res.json();
        console.log(res);
        const userProjectsInput = document.getElementById('user_projects');
        if(res){
            userProjectsInput.innerHTML = res.map((project) =>{
                return `<option value="${project.id}">${project.name}</option>`;
            })
        }else{
            userProjectsInput.innerHTML = '';
        }
    }
</script>
</x-layout>