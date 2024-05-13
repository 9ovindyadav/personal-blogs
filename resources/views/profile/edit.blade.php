<x-layout title="Edit: {{ $user->name }}">
<div class="flex min-h-full flex-col justify-center p-2 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Update Profile</h2>
  </div>

  <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-3" action="/profile/update/{{ $user->username }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
            <div class="mt-2">
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    required 
                    value="{{ $user->name }}"
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
                    value="{{ $user->username }}" 
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
                    value="{{ $user->email }}"
                    class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="profession" class="block text-sm font-medium leading-6 text-gray-900">Profession</label>
            <div class="mt-2">
                <input 
                    id="profession" 
                    name="profession" 
                    type="text"   
                    value="{{ $user->profession }}"
                    class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            @error('profession')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="about-info" class="block text-sm font-medium leading-6 text-gray-900">Bio</label>
            <div class="mt-2">
                <textarea 
                    id="about-info" 
                    name="about_info" 
                    rows="3"
                    maxlength="255"
                    class="w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    >{{ $user->about_info }}</textarea>
            </div>
            @error('about_info')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="about-info" class="block text-sm font-medium leading-6 text-gray-900">Profile Image</label>
            <div class="mt-2">
                <div class="w-full relative border-2 border-gray-300 border-dashed rounded-lg p-6" id="dropzone">
                    <input type="file" name="profile_img" class="absolute inset-0 w-full h-full opacity-0 z-50" id="file-upload" accept="image/jpeg,image/png,image/gif,image/jpg">
                    <div class="text-center">
                        <img class="mx-auto h-12 w-12" src="https://www.svgrepo.com/show/357902/image-upload.svg" alt="">

                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            <label for="file-upload" class="relative cursor-pointer">
                                <span>Drag and drop</span>
                                <span class="text-indigo-600"> or browse</span>
                                <span>to upload</span>
                            </label>
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            PNG, JPG, GIF up to 10MB
                        </p>
                    </div>

                    <img src="{{ $user->profile_img }}" class="mt-4 mx-auto max-h-40 {{ $user->profile_img ? '' : 'hidden' }}" id="preview">
                </div>
            </div>
            @error('profile_img')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-around">
            <a href="/profile/{{ $user->username }}" class="flex w-[30%] justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancel
            </a>
            <button type="submit" class="flex w-[30%] justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
            </button>
        </div>
    </form>
  </div>
</div>

<script>
    var dropzone = document.getElementById('dropzone');

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('border-indigo-600');
    });

    dropzone.addEventListener('dragleave', e => {
        e.preventDefault();
        dropzone.classList.remove('border-indigo-600');
    });

    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('border-indigo-600');
        var file = e.dataTransfer.files[0];
        console.log(file);
        displayPreview(file);
    });

    var input = document.getElementById('file-upload');

    input.addEventListener('change', e => {
        var file = e.target.files[0];
        displayPreview(file);
    });

    function displayPreview(file) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
            var preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.classList.remove('hidden');
        };
    }
</script>

</x-layout>