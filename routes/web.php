<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Models\Blog;
use App\Models\User;

Route::impersonate();

Route::get('profile/{user:username}', [ProfileController::class,'index']);

Route::middleware('guest')->group(function (){
    Route::get('signup', [RegisterController::class,'create']);
    Route::post('signup', [RegisterController::class,'store']);

    Route::get('login', [SessionController::class,'create'])->name('login');
    Route::post('login', [SessionController::class,'store']);
});

Route::middleware(['auth','verified'])->group(function (){
    
    Route::get('profile/{user:username}/edit', [ProfileController::class,'edit']);
    Route::post('profile/update', [ProfileController::class,'update']);

    Route::get('/contacts',[ContactController::class,'index']);
    Route::get('/contact/create',[ContactController::class,'create']);
    Route::post('/contact/store',[ContactController::class,'store']);
    Route::get('/contact/{contact:id}/edit',[ContactController::class,'edit']);
    Route::post('/contact/update',[ContactController::class,'update']);
    Route::get('/contact/{contact:id}/delete',[ContactController::class,'delete']);

    Route::get('/projects',[ProjectController::class,'index']);
    Route::get('/project/create',[ProjectController::class,'create']);
    Route::post('/project/store',[ProjectController::class,'store']);
    Route::get('/project/{project:id}/edit',[ProjectController::class,'edit']);
    Route::post('/project/update',[ProjectController::class,'update']);
    Route::get('/project/{project:id}/delete',[ProjectController::class,'delete']);

    Route::get('/project/{project:id}/tasks',[ProjectController::class,'tasks']);
    Route::post('/user/{user:id}/projects',[ProfileController::class,'projects']);

    Route::get('/tasks',[TaskController::class,'index']);
    Route::get('/task/create',[TaskController::class,'create']);
    Route::post('/task/store',[TaskController::class,'store']);
    Route::get('/task/{task:id}/edit',[TaskController::class,'edit']);
    Route::post('/task/update',[TaskController::class,'update']);
    Route::get('/task/{task:id}/delete',[TaskController::class,'delete']);

    Route::get('/chats',[ChatController::class,'index']);
    Route::post('/message/send',[ChatController::class,'messageSend']);
    Route::post('/messages',[ChatController::class,'getMessages']); 
});

Route::middleware(['auth','verified','admin'])->prefix('admin')->group(function (){
    Route::get('', [AdminController::class,'index']);
    Route::get('/users', [AdminUserController::class,'index']);
    Route::get('/user/{user:username}', [AdminUserController::class,'view']);
    Route::get('/user/create', [AdminUserController::class,'create']);
    Route::get('/user/store', [AdminUserController::class,'store']);
    Route::get('/user/{user:username}/edit', [AdminUserController::class,'edit']);
    Route::post('/user/update', [AdminUserController::class,'update']);

    Route::get('/projects', [AdminController::class,'projects']);
    Route::get('/tasks', [AdminController::class,'tasks']);

    Route::get('/settings', [AdminController::class,'settings']);
});

Route::middleware('auth')->group(function(){
    Route::get('logout', [SessionController::class,'destroy'])->middleware('auth');

    Route::get('/email/verify',[EmailVerifyController::class,'index'])
        ->name('verification.notice');
        
    Route::post('/email/verification-notification',[EmailVerifyController::class,'notify'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/email/verify/{id}/{hash}',[EmailVerifyController::class,'verify'])
        ->middleware('signed')
        ->name('verification.verify');   
});


Route::get('/', function (Request $request) {

    $blogs = Blog::with('category','author')->orderBy('published_at','desc')->cursorPaginate(10);
    
    return view('blogs',['blogs' => $blogs]);
});

Route::get('blog/{blog:slug}', function (Blog $blog) {

    return view('blog',[
        'blog' => $blog
    ]);
});