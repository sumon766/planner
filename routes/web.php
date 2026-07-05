<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    //Routine Task Routes
    /*
|--------------------------------------------------------------------------
| Routine Tasks
|--------------------------------------------------------------------------
*/

    Route::prefix('routine-tasks')
        ->name('routine-tasks.')
        ->group(function () {

            Route::view('/', 'routine-tasks.index')
                ->name('index');

            Route::view('/create', 'routine-tasks.create')
                ->name('create');

            Route::get('/{routineTask}/edit', function (RoutineTask $routineTask) {

                abort_unless($routineTask->user_id === Auth::id(), 403);

                return view('routine-tasks.edit', [
                    'routineTask' => $routineTask,
                ]);

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
