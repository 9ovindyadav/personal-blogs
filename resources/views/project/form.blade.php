<x-layout title="{{ isset($project) ? 'Edit Project: '.$project->name : 'Create New Project' }}">
<div class="flex min-h-full flex-col justify-center p-2 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ $formTitle }}</h2>
  </div>

  <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-3" action="/project/{{ isset($project) ? 'update' : 'store' }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($project))
            <input type="hidden" name="project_id" value="{{ $project->id }}">
        @endif

        @include('components.form.text',
                [
                    'label' => 'Name',
                    'name' => 'name',
                    'value' => $project->name ?? null,
                    'attributes' => [
                                        'id' => 'name'
                                    ]
                ]
        )

        @include('components.form.select',
                [
                    'label' => 'Assigned To',
                    'name' => 'assigned_to',
                    'value' => $users,
                    'selected' => $project->assigned_to ?? null,
                    'attributes' => [
                                        'id' => 'assigned_to'
                                    ]
                ]
        )

        <div class="flex justify-around">
            <a href="/profile/{{ auth()->user()->username }}" class="flex w-[30%] justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancel
            </a>
            <button type="submit" class="flex w-[30%] justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
            </button>
        </div>
    </form>
  </div>
</div>

</x-layout>