<x-layout title="Admin | {{ auth()->user()->name }}">
    <div class="flex flex-col overflow-x-auto">
        <div class="sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                    <table
                    class="min-w-full text-start text-sm font-light text-surface dark:text-white">
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
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>