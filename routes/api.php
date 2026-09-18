<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/register', [
        AuthController::class,
        'register',
    ])->middleware('throttle:auth');

    Route::post('/login', [
        AuthController::class,
        'login',
    ])->middleware('throttle:auth');


    /*
    |--------------------------------------------------------------------------
    | Authenticated API
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', [
            AuthController::class,
            'logout',
        ]);

        Route::get('/user', [
            AuthController::class,
            'user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */

        Route::apiResource(
            'projects',
            ProjectController::class
        );

        Route::post(
            '/projects/{project}/members',
            [ProjectController::class, 'addMember']
        );

        Route::delete(
            '/projects/{project}/members/{user}',
            [ProjectController::class, 'removeMember']
        );


        /*
        |--------------------------------------------------------------------------
        | Tasks
        |--------------------------------------------------------------------------
        */

        Route::scopeBindings()->group(function () {

            Route::apiResource(
                'projects.tasks',
                TaskController::class
            );

            Route::patch(
                '/projects/{project}/tasks/{task}/status',
                [TaskController::class, 'status']
            );

            Route::post(
                '/projects/{project}/tasks/{task}/labels',
                [TaskController::class, 'attachLabel']
            );

            Route::delete(
                '/projects/{project}/tasks/{task}/labels/{label}',
                [TaskController::class, 'detachLabel']
            );

            // Comments
            Route::get(
                '/tasks/{task}/comments',
                [CommentController::class, 'index']
            );

            Route::post(
                '/tasks/{task}/comments',
                [CommentController::class, 'store']
            );

            Route::put(
                '/comments/{comment}',
                [CommentController::class, 'update']
            );

            Route::delete(
                '/comments/{comment}',
                [CommentController::class, 'destroy']
            );
        });
    });
});
