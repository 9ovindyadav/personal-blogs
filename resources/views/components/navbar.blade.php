<nav class="sticky z-10 w-full top-0 bg-gray-800">
    <div class="mx-auto max-w-8xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <img class="h-8 w-8" src="{{ asset('images/logo.png') }}" alt="Your Company">
                    <h2 class="text-white ml-3">{{ env('APP_NAME') }}</h2>
                </a>
            </div>

            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6 space-x-4">
                    @auth
                        @if (auth()->user()->is_admin)
                        <x-nav-link href="/admin" :active="request()->is('admin')">Admin</x-nav-link>
                        @endif
  
                        <x-nav-link href="/profile/{{ auth()->user()->username }}" :active="request()->is('profile')">Welcome, {{ auth()->user()->name }}</x-nav-link>
                        <x-nav-link href="/projects" :active="request()->is('projects')">Projects</x-nav-link>
                        <x-nav-link href="/tasks" :active="request()->is('tasks')">Tasks</x-nav-link>
                        <x-nav-link href="/chats" :active="request()->is('chats')">Chats</x-nav-link>
                        <x-nav-link href="/contacts" :active="request()->is('contacts')">Contacts</x-nav-link>
                        @impersonating($guard = null)
                            <x-nav-link href="{{ route('impersonate.leave') }}">Leave impersonation</x-nav-link>
                        @else
                        <x-nav-link href="/logout">Logout</x-nav-link>
                        @endImpersonating
                    @else
                        <x-nav-link href="/login" :active="request()->is('login')">Login</x-nav-link>
                        <x-nav-link href="/signup" :active="request()->is('signup')">Sign Up</x-nav-link>
                    @endauth
                </div>
            </div>  
        </div>
    </div>
</nav>