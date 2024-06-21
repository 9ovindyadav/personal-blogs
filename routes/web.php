<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Models\Blog;
use App\Models\User;

Route::get('/', function () {
    $blogs = Blog::with('category','author')->orderBy('published_at','desc')->cursorPaginate(10);

    return view('blogs',['blogs' => $blogs]);
});

Route::get('blog/{blog:slug}', function (Blog $blog) {
    
    return view('blog',[
        'blog' => $blog
    ]);
});

Route::middleware('guest')->group(function (){
    Route::get('signup', [RegisterController::class,'create']);
    Route::post('signup', [RegisterController::class,'store']);

    Route::get('login', [SessionController::class,'create'])->name('login');
    Route::post('login', [SessionController::class,'store']);
});

Route::get('profile/{user:username}', [ProfileController::class,'index']);

Route::middleware('auth')->group(function (){
    Route::get('logout', [SessionController::class,'destroy']);
    Route::get('profile/edit/{user:username}', [ProfileController::class,'edit']);
    Route::post('profile/update/{user:username}', [ProfileController::class,'update']);
});

Route::middleware(['auth','admin'])->prefix('admin')->group(function (){
    Route::get('', [AdminController::class,'index']);
    Route::get('/users', [AdminUserController::class,'index']);
    Route::get('/user/{user:username}', [AdminUserController::class,'view']);
    Route::get('/user/create', [AdminUserController::class,'create']);
    Route::get('/user/store', [AdminUserController::class,'store']);
    Route::get('/user/{user:username}/edit', [AdminUserController::class,'edit']);
    Route::post('/user/update', [AdminUserController::class,'update']);
    Route::get('/settings', [AdminController::class,'settings']);
});

Route::impersonate();