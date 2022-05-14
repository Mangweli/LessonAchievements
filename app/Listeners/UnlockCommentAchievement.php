<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UnlockCommentAchievement
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $user_id       = $event->Comment_user_id;
        // $commentNumber = DB::Table("Comments")
        //                     ->where("User_id", $user_id)
        //                     ->count();

        // $qualifiedAchievement = DB::Table("comment_written_achievements")
        //                             ->where("number_of_comments", $commentNumber)
        //                             ->get();

        //check_qualified achievement
        //check_if_already_has_achievement
        //assignAchievement
    }
}
