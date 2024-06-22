@extends('admin.layout')

@section('content')
<div>
    <div class="p-3">
        <a href="/admin/user/create" class="text-gray-600 hover:bg-gray-500 hover:text-white rounded-md px-3 py-1 text-sm font-medium">New User</a>
    </div>
    <table class="min-w-full text-start text-sm font-light text-surface dark:text-white">
    <thead
        class="border-b border-neutral-200 font-medium dark:border-white/10">
        <tr>
        <th scope="col" class="px-6 py-2">ID</th>
        <th scope="col" class="px-6 py-2">Name</th>
        <th scope="col" class="px-6 py-2">Username</th>
        <th scope="col" class="px-6 py-2">Email</th>
        <th scope="col" class="px-6 py-2">Profession</th>
        <th scope="col" class="px-6 py-2">Role</th>
        <th scope="col" class="px-6 py-2">Joining Date</th>
        <th scope="col" class="px-6 py-2">Pseudo Login</th>
        <th scope="col" class="px-6 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr class="border-b border-neutral-200 dark:border-white/10 text-center">
            <td class="whitespace-nowrap px-6 py-2 font-medium">{{ $user->id }}</td>
            <td class="whitespace-nowrap px-6 py-2">{{ $user->name }}</td>
            <td class="whitespace-nowrap px-6 py-2">
                <a href="/profile/{{ $user->username }}" class="underline">
                    {{ $user->username }}
                </a>
            </td>
            <td class="whitespace-nowrap px-6 py-2">{{ $user->email }}</td>
            <td class="whitespace-nowrap px-6 py-2">{{ ucfirst($user->profession) }}</td>
            <td class="whitespace-nowrap px-6 py-2">
                <span class="text-gray-600 text-sm font-medium">
                {{ $user->is_admin ? 'Admin' : 'User' }}
                </span>
            </td>
            <td class="whitespace-nowrap px-6 py-2">{{ $user->created_at->format('d M Y H:i A') }}</td>
            <td class="whitespace-nowrap px-6 py-2">
                @canBeImpersonated($user, $guard = null)
                <a href="{{ route('impersonate', $user->id) }}" class="text-gray-600 hover:bg-gray-500 hover:text-white rounded-md px-3 py-1 text-sm font-medium">Impersonate</a>
                @endCanBeImpersonated
            </td>
            <td class="flex justify-between p-2">
                <a class="text-sm text-yellow-700 hover:underline" href="/admin/user/{{ $user->username }}">View</a>
                <a class="text-sm text-red-700 hover:underline" href="/admin/user/{{ $user->username }}/edit">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection