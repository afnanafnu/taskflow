<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;

use App\Repositories\CommentRepository;
use App\Repositories\Contracts\CommentRepositoryInterface;

use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

use App\Repositories\DashboardRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Project Repository
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            ProjectRepositoryInterface::class,
            ProjectRepository::class
        );


        /*
        |--------------------------------------------------------------------------
        | Task Repository
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );


        /*
        |--------------------------------------------------------------------------
        | Comment Repository
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );


        /*
        |--------------------------------------------------------------------------
        | Dashboard Repository
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            DashboardRepositoryInterface::class,
            DashboardRepository::class
        );


        /*
        |--------------------------------------------------------------------------
        | User Repository
        |--------------------------------------------------------------------------
        */

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Authentication Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('auth', function ($request) {
            return Limit::perMinute(10)
                ->by($request->ip());
        });


        /*
        |--------------------------------------------------------------------------
        | Policies
        |--------------------------------------------------------------------------
        */

        Gate::policy(
            Project::class,
            ProjectPolicy::class
        );

        Gate::policy(
            Task::class,
            TaskPolicy::class
        );

        Gate::policy(
            Comment::class,
            CommentPolicy::class
        );

        Gate::policy(
            User::class,
            UserPolicy::class
        );
    }
}