<?php

use App\Livewire\ChatBox;
use App\Livewire\Dashboard;
use App\Livewire\ShowFriends;
use App\Livewire\ShowUsers;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/users', ShowUsers::class)->name('users');
    Route::get('/friends', ShowFriends::class)->name('friends');
    Route::get('/chat', ChatBox::class)->name('chat');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



require __DIR__.'/auth.php';
