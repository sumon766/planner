<?php

use App\Models\RoutineTask;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    /*
    |--------------------------------------------------------------------------
    | Routine Tasks
    |--------------------------------------------------------------------------
    */
    Route::prefix('routine-tasks')
        ->name('routine-tasks.')
        ->group(function () {

            // List
            Route::view('/', 'routine-tasks.index')
                ->name('index');

            // Create
            Route::view('/create', 'routine-tasks.create')
                ->name('create');

            // Edit
            Route::get('/{task}/edit', function (RoutineTask $task) {

                abort_unless($task->user_id === Auth::id(), 403);

                return view('routine-tasks.edit', compact('task'));

            })->name('edit');

    });

    /*
    |--------------------------------------------------------------------------
    | Extra Tasks
    |--------------------------------------------------------------------------
    */
    Route::prefix('extra-tasks')
        ->name('extra-tasks.')
        ->group(function () {

            // List
            Route::view('/', 'extra-tasks.index')
                ->name('index');

            // Create
            Route::view('/create', 'extra-tasks.create')
                ->name('create');

            // Edit
            Route::get('/{task}/edit', function (ExtraTask $task) {

                abort_unless($task->user_id === Auth::id(), 403);

                return view('extra-tasks.edit', compact('task'));

            })->name('edit');

    });
});

Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

require __DIR__.'/auth.php';
