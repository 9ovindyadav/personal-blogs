<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProfileController;

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

Route::middleware('guest')->group(function (){
    Route::get('signup', [RegisterController::class,'create']);
    Route::post('signup', [RegisterController::class,'store']);

    Route::get('login', [SessionController::class,'create']);
    Route::post('login', [SessionController::class,'store']);
});

Route::get('profile/{user:username}', [ProfileController::class,'index']);

Route::middleware('auth')->group(function (){
    Route::get('logout', [SessionController::class,'destroy']);
    Route::get('profile/edit/{user:username}', [ProfileController::class,'edit']);
    Route::post('profile/update/{user:username}', [ProfileController::class,'update']);
});