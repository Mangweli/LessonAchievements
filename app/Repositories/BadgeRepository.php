<?php

namespace App\Repositories;

use App\Interfaces\BadgeRepositoryInterface;
use App\Models\Achievements\Badge;
use App\Models\BadgeUserAchievement;

class BadgeRepository implements BadgeRepositoryInterface
{
    public function getBadgeAchievement(int $achievementNumber)
    {
       return Badge::where("number_of_achievements", "<=" ,$achievementNumber)->orderBy('id', 'desc')->first();
    }

    public function userHasAchievement($user_id, $badge_id)
    {
        return BadgeUserAchievement::where("user_id", $user_id)->where("badge_id", $badge_id)->count();
    }

    public function setUserBadgeAchievement($user_id, $badge_id) {
        return BadgeUserAchievement::create(["user_id" => $user_id, "badge_id" => $badge_id]);
    }
}
