<x-layout title="Sign Up">
<div class="flex min-h-full flex-col justify-center p-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Create a new account</h2>
  </div>

  <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-3" action="/signup" method="POST">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
            <div class="mt-2">
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    required 
                    value="{{ old('name') }}"
                    class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            @error('name')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
            <div class="mt-2">
                <input 
                    id="username" 
                    name="username" 
                    type="text" 
                    required
                    value="{{ old('username') }}" 
                    class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            @error('username')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
            <div class="mt-2">
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    autocomplete="email" 
                    required 
                    value="{{ old('email') }}"
                    class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
            <div class="mt-2">
                <input 
                    id="password" 
                    name="password" 
                    type="password"  
                    required 
                    value="{{ old('password') }}"
                    class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign Up</button>
        </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
      Already a member?
      <a href="/login" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login</a>
    </p>
  </div>
</div>

</x-layout>