<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        return view('user.list',['users' => $users]);
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
