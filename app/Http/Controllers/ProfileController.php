<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $user->with('blogs');

        return view('profile.index',['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('profile.edit',['user' => $user]);
    }

    public function update()
    {
        $user = auth()->user();

        $attributes = request()->validate([
            'name' => 'required|max:255',
            'username' => [
                'required','min:3','max:255',Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required','email','max:255',Rule::unique('users')->ignore($user->id)
            ],
            'profession' => 'nullable',
            'about_info' => 'nullable',
            'profile_img' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        if(request()->hasFile('profile_img')){
            $uploadedFile = request()->file('profile_img');
            $path = $uploadedFile->store('public/profile_images');

            $attributes['profile_img'] = Storage::url($path);
        }

        $user->update($attributes);

        return redirect('/profile/'.$user->username)->with('success','Profile updated successfully');
    }
}
