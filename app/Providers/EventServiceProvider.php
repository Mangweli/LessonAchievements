<?php

namespace App\Providers;

use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\UnlockCommentAchievement;
use App\Listeners\UnlockLessonWatchedAchievement;
use App\Listeners\UnlockBadgeAchievement;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentWritten::class => [
            UnlockCommentAchievement::class,
            UnlockBadgeAchievement::class
        ],
        LessonWatched::class => [
            UnlockLessonWatchedAchievement::class,
            UnlockBadgeAchievement::class
        ],
        AchievementUnlocked::class => [

        ],
        BadgeUnlocked::class => [

        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
