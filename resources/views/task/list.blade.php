@extends('admin.layout')

@section('content')
<div>
    <div class="p-3">
        <a href="/task/create" class="text-gray-600 hover:bg-gray-500 hover:text-white rounded-md px-3 py-1 text-sm font-medium">New Task</a>
    </div>
    <table class="min-w-full text-start text-sm font-light text-surface dark:text-white">
    <thead
        class="border-b border-neutral-200 font-medium dark:border-white/10">
        <tr>
            <th scope="col" class="px-6 py-2">ID</th>
            <th scope="col" class="px-6 py-2">Subject</th>
            <th scope="col" class="px-6 py-2">Status</th>
            <th scope="col" class="px-6 py-2">Project</th>
            <th scope="col" class="px-6 py-2">Assigned To</th>
            <th scope="col" class="px-6 py-2">Created By</th>
            <th scope="col" class="px-6 py-2">Last Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
        <tr class="border-b border-neutral-200 dark:border-white/10 text-center">
            <td class="whitespace-nowrap px-6 py-2 font-medium">{{ $task->id }}</td>
            <td class="whitespace-nowrap px-6 py-2">{{ $task->subject }}</td>
            <td class="whitespace-nowrap px-6 py-2"></td>
            <td class="whitespace-nowrap px-6 py-2">{{ $task->relation('project','M:M',true)->first()->name }}</td>
            <td class="whitespace-nowrap px-6 py-2">{{ $task->assigned_to()->name }}</td>
            <td class="whitespace-nowrap px-6 py-2">{{ $task->created_by()->name }}</td>
            <td class="whitespace-nowrap px-6 py-2">{{ $task->created_at->format('d M Y H:i A') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection