<?php

namespace App\Repositories;

use App\Interfaces\BadgeRepositoryInterface;
use App\Models\Achievements\Badge;
use App\Models\BadgeUserAchievement;

class BadgeRepository implements BadgeRepositoryInterface
{
    public function getUserNextBadgeAchievement($user_id)
    {
        $currentAchievement = BadgeUserAchievement::where("user_id", $user_id)
                                                    ->orderBy("created_at","desc")
                                                    ->first();

        if(empty($currentAchievement)) {
            return Badge::orderBy("number_of_achievements")->first();
        }

        return Badge::where("number_of_achievements", ">", $currentAchievement->badge_id)
                    ->first();
    }

    public function getBadgeAchievement(int $achievementNumber)
    {
       return Badge::where("number_of_achievements", "<=" ,$achievementNumber)->orderBy('id', 'desc')->first();
    }

    public function getUserCurrentBadge($user_id)
    {
        return BadgeUserAchievement::where("user_id",$user_id)
                                    ->join("badges", "badges.id","=", "badge_user_achievements.badge_id")
                                    ->first();
    }

    public function userHasAchievement($user_id, $badge_id)
    {
        return BadgeUserAchievement::where("user_id", $user_id)->where("badge_id", $badge_id)->count();
    }

    public function setUserBadgeAchievement($user_id, $badge_id) {
        return BadgeUserAchievement::create(["user_id" => $user_id, "badge_id" => $badge_id]);
    }
}
