<x-layout title="Projects | {{ auth()->user()->name }}">
<div>
    <div class="p-3">
        <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ $pageTitle }}</h2>
        <a href="/project/create" class="text-gray-600 hover:bg-gray-500 hover:text-white rounded-md px-3 py-1 text-sm font-medium">Add Project</a>
    </div>

    @if($projects->isNotEmpty())
            <table class="min-w-1/2 text-start text-sm font-light text-surface dark:text-white">
                <thead
                    class="border-b border-neutral-200 font-medium dark:border-white/10">
                    <tr>
                    <th scope="col" class="px-6 py-2">ID</th>
                    <th scope="col" class="px-6 py-2">Name</th>
                    <th scope="col" class="px-6 py-2">Tasks</th>
                    <th scope="col" class="px-6 py-2">Assigned To</th>
                    <th scope="col" class="px-6 py-2">Last Updated</th>
                    <th scope="col" class="px-6 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                    <tr class="border-b border-neutral-200 dark:border-white/10 text-center">
                        <td class="whitespace-nowrap px-6 py-2 font-medium">{{ $project->id }}</td>
                        <td class="whitespace-nowrap px-6 py-2">{{ $project->name }}</td>
                        <td class="whitespace-nowrap px-6 py-2">
                            {{ $project->relation('task','M:M')->count() }} (<a class="text-xs text-blue-700 hover:underline" href="/project/{{ $project->id }}/tasks">View</a>)
                        </td>
                        <td class="whitespace-nowrap px-6 py-2">{{ $project->assigned_to()->name }}</td>
                        <td class="whitespace-nowrap px-6 py-2">{{ $project->created_at->format('d M Y H:i A') }}</td>
                        <td class="flex justify-between p-2">
                            <a class="text-sm text-yellow-700 hover:underline" href="/project/{{ $project->id }}/edit">Edit</a>
                            <a class="text-sm text-red-700 hover:underline" href="/project/{{ $project->id }}/delete">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p class="text-sm text-center px-5">No Projects</p>
            @endif
    
</div>
</x-layout>