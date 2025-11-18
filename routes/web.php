<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/chats', [MessageController::class, 'index'])->name('chats.index');
    Route::get('/chat/{user}', [MessageController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}/send', [MessageController::class, 'store'])->name('message.send');
    Route::get('/chat/{user}/messages', [MessageController::class, 'getMessages'])->name('chat.messages');
    Route::get('/api/chats', [MessageController::class, 'getChats'])->name('api.chats');

    Route::get('/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/search/users', [UserController::class, 'searchAjax'])->name('users.search.ajax');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}/view', [ProfileController::class, 'show'])->name('profile.show');
});
