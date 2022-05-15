<?php

namespace App\Repositories;

use App\Interfaces\LessonRepositoryInterface;
use App\Models\LessonUser;
use App\Models\Achievements\LessonWatchedAchievement;
use App\Models\Users\UserLessonWatchedAchievement;

class LessonRepository implements LessonRepositoryInterface
{
    /**
     * get user next lesson achievement
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getUserNextLessonAchievement($user_id)
    {
        $currentAchievement = UserLessonWatchedAchievement::where("user_id", $user_id)
                                                            ->orderBy("lesson_achievement_id","desc")
                                                            ->first();

        if(empty($currentAchievement)) {
            return LessonWatchedAchievement::orderBy("number_of_lessons")->first();
        }

        return LessonWatchedAchievement::where("number_of_lessons", ">", $currentAchievement->lesson_achievement_id)
                                        ->first();
    }

    /**
     * get user current lesson number stage
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getUserLessonNumber($user_id) {
        return LessonUser::where("user_id", $user_id)->distinct("lesson_id")->count();
    }

    /**
     * get lesson achievement based on lessons watched
     *
     * @param  mixed $lessonNumber
     * @return void
     */
    public function getLessonAchievement($lessonNumber)
    {
       return LessonWatchedAchievement::where("number_of_lessons", $lessonNumber)->first();
    }

    /**
     * get lesson achievements received by a user
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getLessonAchievementReceived(int $user_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)->count();
    }

    /**
     * get all the lessons achieved by a user
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getAllUserLessonAchievements($user_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)
                                            ->join("lesson_watched_achievements", "lesson_watched_achievements.id", "=", "user_lesson_watched_achievements.lesson_achievement_id")
                                            ->pluck("name")
                                            ->toArray();
    }

    /**
     * check if a user has gotten a specific achievement
     *
     * @param  mixed $user_id
     * @param  mixed $achievement_id
     * @return void
     */
    public function userHasAchievement($user_id, $achievement_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)
                                            ->where("lesson_achievement_id", $achievement_id)
                                            ->count();
    }

    /**
     * set the watch status of a specific user
     *
     * @param  mixed $user_id
     * @param  mixed $lesson_id
     * @param  mixed $watch_status
     * @return void
     */
    public function setLessonWatchStatus($user_id, $lesson_id, $watch_status)
    {
        return LessonUser::create(["user_id" => $user_id, "lesson_id" => $lesson_id, "watched" => $watch_status]);
    }

    /**
     * assign a lesson achievement to a specific user
     *
     * @param  mixed $user_id
     * @param  mixed $lesson_id
     * @param  mixed $achievement_id
     * @return void
     */
    public function setUserLessonAchievement($user_id, $lesson_id, $achievement_id)
    {
       return UserLessonWatchedAchievement::create([
                                                "user_id"               => $user_id,
                                                "lesson_achievement_id" => $achievement_id,
                                                "lesson_id"             => $lesson_id
                                            ]);
    }





}
