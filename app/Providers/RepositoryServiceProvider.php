<?php

namespace App\Providers;

use App\Interfaces\BadgeRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Interfaces\LessonRepositoryInterface;
use App\Repositories\BadgeRepository;
use App\Repositories\CommentRepository;
use App\Repositories\LessonRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BadgeRepositoryInterface::class, BadgeRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
