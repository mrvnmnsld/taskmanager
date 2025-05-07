<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}/view', [TaskController::class, 'view'])->name('tasks.view');

    //main task
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/update/field', [TaskController::class, 'updateField']);

    Route::post('/tasks/{id}/done', [TaskController::class, 'markDone'])->name('tasks.done');
    Route::post('/tasks/{id}/reopen', [TaskController::class, 'reopen'])->name('tasks.reopen');
    Route::post('/tasks/{id}/archive', [TaskController::class, 'archive'])->name('tasks.archive');
    
    //main task


    // subtasks
    Route::post('/tasks/add/subtasks', [TaskController::class, 'addSubtask'])->name('tasks.add.subtasks');
    Route::post('/tasks/done/subtasks', [TaskController::class, 'doneSubtask'])->name('tasks.done.subtasks');
    Route::post('/tasks/remove/subtasks', [TaskController::class, 'removeSubtask'])->name('tasks.remove.subtasks');
    // subtasks

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
