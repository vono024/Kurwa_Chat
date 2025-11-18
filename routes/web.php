<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/chat/{user}/messages', [MessageController::class, 'getMessages'])->name('chat.messages');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/chats', [MessageController::class, 'index'])->name('chats.index');

    Route::get('/chat/{user}', [MessageController::class, 'show'])->name('chat.show');

    Route::post('/chat/{user}/send', [MessageController::class, 'store'])->name('message.send');

    Route::get('/search', [UserController::class, 'search'])->name('users.search');

    Route::get('/profile/{user}', [UserController::class, 'show'])->name('user.profile');
});
