<x-layout title="Admin | {{ auth()->user()->name }}">
        <div class="flex h-auto">
            @include('admin.sidebar')
            @yield('content')
        </div>
</x-layout>