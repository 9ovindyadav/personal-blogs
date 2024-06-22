<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        return view('user.list',['users' => $users]);
    }

    public function view(User $user)
    {
        return view('user.view',['user' => $user]);
    }

    public function create()
    {
        return view('user.form',['formTitle' => 'Create New User']);
    }

    public function store()
    {

    }

    public function edit(User $user)
    {
        return view('user.form',['user' => $user, 'formTitle' => 'Edit User']);
    }

    public function update()
    {
        $user = User::find(request()->input('user_id'));

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
            'profile_img' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'is_admin' => ['int']
        ]);

        if(request()->hasFile('profile_img')){
            $uploadedFile = request()->file('profile_img');
            $path = $uploadedFile->store('public/profile_images');

            $attributes['profile_img'] = Storage::url($path);
        }

        $user->update($attributes);

        return redirect('/admin/users')->with('success','User updated successfully');
    }
}
