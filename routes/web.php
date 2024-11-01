<?php

use App\Http\Controllers\{ChatController, ChatSocketController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat3', [ChatController::class, 'index2'])->name('chat.index2');

Route::post('/chat/messages', [ChatController::class, 'storeMessage'])->name('chat.storeMessage');
Route::get('/chat/messages/{canalId}', [ChatController::class, 'getMessages'])->name('chat.getMessages');


Route::get('/chat3', [ChatSocketController::class, 'index'])->name('chat.index');
Route::post('/chat3/messages', [ChatSocketController::class, 'storeMessage'])->name('chat.storeMessage');
Route::get('/chat/messages/{canalId}', [ChatSocketController::class, 'getMessages'])->name('chat.getMessages');