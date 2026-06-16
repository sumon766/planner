<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    //Routine Task Routes
    Route::get('/routine-tasks', function () {
        return view('routine-tasks.index');
    })->name('routine-tasks.index');

    Route::get('/routine-tasks/create', function () {
        return view('routine-tasks.create');
    })->name('routine-tasks.create');
});

Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

require __DIR__.'/auth.php';
