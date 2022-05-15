<?php

namespace App\Repositories;

use App\Interfaces\LessonRepositoryInterface;
use App\Models\LessonUser;
use App\Models\Achievements\LessonWatchedAchievement;
use App\Models\Users\UserLessonWatchedAchievement;

class LessonRepository implements LessonRepositoryInterface
{
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

    public function getUserLessonNumber($user_id) {
        return LessonUser::where("user_id", $user_id)->distinct("lesson_id")->count();
    }

    public function getLessonAchievement($lessonNumber)
    {
       return LessonWatchedAchievement::where("number_of_lessons", $lessonNumber)->first();
    }

    public function getLessonAchievementReceived(int $user_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)->count();
    }

    public function getAllUserLessonAchievements($user_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)
                                            ->join("lesson_watched_achievements", "lesson_watched_achievements.id", "=", "user_lesson_watched_achievements.lesson_achievement_id")
                                            ->pluck("name")
                                            ->toArray();
    }

    public function userHasAchievement($user_id, $achievement_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)
                                            ->where("lesson_achievement_id", $achievement_id)
                                            ->count();
    }

    public function setLessonWatchStatus($user_id, $lesson_id, $watch_status)
    {
        return LessonUser::create(["user_id" => $user_id, "lesson_id" => $lesson_id, "watched" => $watch_status]);
    }

    public function setUserLessonAchievement($user_id, $lesson_id, $achievement_id)
    {
       return UserLessonWatchedAchievement::create([
                                                "user_id"               => $user_id,
                                                "lesson_achievement_id" => $achievement_id,
                                                "lesson_id"             => $lesson_id
                                            ]);
    }





}
