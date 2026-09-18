<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'projects',
        ProjectController::class
    );


    /*
    |--------------------------------------------------------------------------
    | All Tasks
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/tasks',
        [TaskController::class, 'all']
    )->name('tasks.index');


    /*
    |--------------------------------------------------------------------------
    | Project Tasks
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'projects.tasks',
        TaskController::class
    );


    Route::patch(
        '/projects/{project}/tasks/{task}/status',
        [TaskController::class, 'status']
    )->name('projects.tasks.status');


    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/tasks/{task}/comments',
        [CommentController::class, 'index']
    )->name('tasks.comments.index');

    Route::post(
        '/tasks/{task}/comments',
        [CommentController::class, 'store']
    )->name('tasks.comments.store');

    Route::put(
        '/comments/{comment}',
        [CommentController::class, 'update']
    )->name('comments.update');

    Route::delete(
        '/comments/{comment}',
        [CommentController::class, 'destroy']
    )->name('comments.destroy');


    /*
    |--------------------------------------------------------------------------
    | Users - Admin only through UserPolicy
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'users',
        UserController::class
    )->except([
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});

require __DIR__.'/auth.php';