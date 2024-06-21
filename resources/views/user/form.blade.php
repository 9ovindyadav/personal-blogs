<x-layout title="{{ isset($user) ? 'Edit Profile: '.$user->name : 'Create New User' }}">
<div class="flex min-h-full flex-col justify-center p-2 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> -->
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ $formTitle }}</h2>
  </div>

  <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-3" action="/admin/user/{{ isset($user) ? 'update' : 'store' }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($user))
            <input type="hidden" name="user_id" value="{{ $user->id }}">
        @endif
        @include('components.form.text',
                [
                    'label' => 'Full Name',
                    'name' => 'name',
                    'value' => $user->name ?? null,
                    'attributes' => [
                                        'id' => 'name',
                                        'required' => true
                                    ]
                ]
        )

        @include('components.form.email',
                [
                    'label' => 'Email',
                    'name' => 'email',
                    'value' => $user->email ?? null,
                    'attributes' => [
                                        'id' => 'email',
                                        'required' => true
                                    ]
                ]
        )
        
        @include('components.form.text',
                [
                    'label' => 'Username',
                    'name' => 'username',
                    'value' => $user->username ?? null,
                    'attributes' => [
                                        'id' => 'username',
                                        'required' => true
                                    ]
                ]
        )

        @include('components.form.text',
                [
                    'label' => 'Profession',
                    'name' => 'profession',
                    'value' => $user->profession ?? null,
                    'attributes' => [
                                        'id' => 'profession'
                                    ]
                ]
        )

        @include('components.form.textarea',
                [
                    'label' => 'Bio',
                    'name' => 'about_info',
                    'value' => $user->about_info ?? null,
                    'attributes' => [
                                        'id' => 'about_info',
                                        'rows' => '3',
                                        'maxlength' => '255'
                                    ]
                ]
        )

        @include('components.form.file',
                [
                    'label' => 'Profile Image',
                    'name' => 'profile_img',
                    'img_link' => $user->profile_img ?? null,
                ]
        )

        <div class="flex justify-around">
            <a href="/admin/users" class="flex w-[30%] justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancel
            </a>
            <button type="submit" class="flex w-[30%] justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
            </button>
        </div>
    </form>
  </div>
</div>

</x-layout>