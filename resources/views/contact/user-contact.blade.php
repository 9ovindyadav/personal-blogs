<x-layout title="Contacts | {{ auth()->user()->name }}">
<div>
    <div class="p-3">
        <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ $pageTitle }}</h2>
        <a href="/contact/create" class="text-gray-600 hover:bg-gray-500 hover:text-white rounded-md px-3 py-1 text-sm font-medium">Add Contact</a>
    </div>

    @if($contacts->isNotEmpty())
        <table class="min-w-1/2 text-start text-sm font-light text-surface dark:text-white">
            <thead
                class="border-b border-neutral-200 font-medium dark:border-white/10">
                <tr>
                <th scope="col" class="px-6 py-2">ID</th>
                <th scope="col" class="px-6 py-2">Phone</th>
                <th scope="col" class="px-6 py-2">Status</th>
                <th scope="col" class="px-6 py-2">Last Updated</th>
                <th scope="col" class="px-6 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                <tr class="border-b border-neutral-200 dark:border-white/10 text-center">
                    <td class="whitespace-nowrap px-6 py-2 font-medium">{{ $contact->id }}</td>
                    <td class="whitespace-nowrap px-6 py-2">{{ $contact->phone }}</td>
                    <td class="whitespace-nowrap px-6 py-2">
                        <span class="text-gray-600 text-sm font-medium">
                        {{ $contact->is_primary ? 'Primary' : '' }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-2">{{ $contact->created_at->format('d M Y H:i A') }}</td>
                    <td class="flex justify-between p-2">
                        <a class="text-sm text-yellow-700 hover:underline" href="/contact/{{ $contact->id }}/edit">Edit</a>
                        <a class="text-sm text-red-700 hover:underline" href="/contact/{{ $contact->id }}/delete">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-sm text-center px-5">No Contacts</p>
        @endif
    
</div>
</x-layout>