<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/feed', [PostController::class, 'index'])->name('feed');

    // CRUD для публікацій
    Route::resource('posts', PostController::class);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/users/{user}/friend-request', [FriendshipController::class, 'sendRequest'])->name('friend.request');
    Route::post('/users/{user}/accept', [FriendshipController::class, 'acceptRequest'])->name('friend.accept');
    Route::post('/users/{user}/reject', [FriendshipController::class, 'rejectRequest'])->name('friend.reject');
    Route::delete('/users/{user}/remove', [FriendshipController::class, 'removeFriend'])->name('friend.remove');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
});
