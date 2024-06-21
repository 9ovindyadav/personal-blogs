<x-layout title="Profile: {{ $user->name }}">
    <div class="p-5 rounded flex flex-col items-center text-gray-500">
        <div class="flex items-center">
            <img class="w-16 h-16 rounded-full mr-3" src="{{ $user->profile_img ? asset($user->profile_img) : asset('images/user-icon.jpeg') }}" alt="Profile Image">
            <div>
                <h2
                    class="text-2xl font-medium leading-none text-gray-900 hover:text-indigo-600 transition duration-500 ease-in-out">
                    {{ $user->name }}
                </h2>
                <p class="text-sm">{{ '@'.$user->username }}</p>
                <p>{{ $user->profession }}</p>
            </div>
        </div>

        <p class="mt-5 text-sm max-w-xl text-center text-gray-900">
            {{ $user->about_info }}
        </p>
        
        <div class="flex mt-4">
            <a href="/#" class="w-6 mx-1">

            </a>
        </div>
        @if(auth()->check() && $user->id === auth()->user()->id)
        <div class="w-1/2 flex justify-end">
            <a href="/profile/edit/{{ $user->username }}">Edit <i class="fa-solid fa-pen-to-square"></i></a>
        </div>
        @endif
    </div>

    <div class="p-5">
        <h2 class="text-xl my-2">Contacts</h2>
        @php
            $contacts = $user->relation('contact')->get();
        @endphp

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
                    <td class="whitespace-nowrap px-6 py-2">{{ $user->created_at->format('d M Y H:i A') }}</td>
                    <td>
                        <a href="/admin/user/{{ $user->username }}">Edit</a>
                        <a href="/admin/user/{{ $user->username }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-sm px-5">No Contacts</p>
        @endif


        <h2 class="text-xl my-2">Projects</h2>
        @php
            $contacts = $user->relation('contact')->get();
        @endphp
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
                @php
                    $contacts = $user->relation('contact')->get();
                @endphp

                @foreach ($contacts as $contact)
                <tr class="border-b border-neutral-200 dark:border-white/10 text-center">
                    <td class="whitespace-nowrap px-6 py-2 font-medium">{{ $contact->id }}</td>
                    <td class="whitespace-nowrap px-6 py-2">{{ $contact->phone }}</td>
                    <td class="whitespace-nowrap px-6 py-2">
                        <span class="text-gray-600 text-sm font-medium">
                        {{ $contact->is_primary ? 'Primary' : '' }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-2">{{ $user->created_at->format('d M Y H:i A') }}</td>
                    <td>
                        <a href="/admin/user/{{ $user->username }}">Edit</a>
                        <a href="/admin/user/{{ $user->username }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-sm px-5">No Projects</p>
        @endif


        <h2 class="text-xl my-10">Blogs</h2>
        @if ($user->blogs->isNotEmpty())
            <div class="grid gap-8 lg:grid-cols-2 my-5">
                @foreach($user->blogs as $blog)
                    <article class="flex flex-col justify-between p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><a href="/blog/{{ $blog->slug }}">{{ $blog->title }}</a></h2>
                        <p class="mb-5 font-light text-gray-500 dark:text-gray-400">
                            {{ $blog->extends }}
                            <a href="/blog/{{ $blog->slug }}" class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">
                                Read more
                            </a>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                                <!-- <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg> -->
                                {{ $blog->category->name }}
                            </span>
                            <span class="text-sm">{{ \Carbon\Carbon::parse($blog->published_at)->diffForHumans() }}</span>
                        </div>
                    </article> 
                @endforeach
            </div>
        @else
            <p class="text-sm px-5">No Blogs</p>
        @endif
    </div>
</x-layout>