<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;

use App\Models\Blog;
use App\Models\User;

Route::get('/', function () {
    $blogs = Blog::with('category','author')->get();

    return view('blogs',[
        'blogs' => $blogs
    ]);
});

Route::get('blog/{blog:slug}', function (Blog $blog) {
    
    return view('blog',[
        'blog' => $blog
    ]);
});

Route::get('profile/{user:username}', function (User $user) {
    
    return view('profile',[
        'blog' => $user
    ]);
});

Route::get('signup', [RegisterController::class,'create'])->middleware('guest');
Route::post('signup', [RegisterController::class,'store'])->middleware('guest');

Route::get('login', [SessionController::class,'create'])->middleware('guest');
Route::post('login', [SessionController::class,'store'])->middleware('guest');
Route::get('logout', [SessionController::class,'destroy'])->middleware('auth');