<?php

namespace App\Repositories;

use App\Interfaces\BadgeRepositoryInterface;
use App\Models\Achievements\Badge;
use App\Models\BadgeUserAchievement;

class BadgeRepository implements BadgeRepositoryInterface
{

    /**
     * get user next badge achievement
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getUserNextBadgeAchievement(int $user_id)
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

    /**
     * get badges achievements
     *
     * @param  mixed $achievementNumber
     * @return void
     */
    public function getBadgeAchievement(int $achievementNumber)
    {
       return Badge::where("number_of_achievements", "<=" ,$achievementNumber)->orderBy('id', 'desc')->first();
    }

    /**
     * get user current badge
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getUserCurrentBadge($user_id)
    {
        return BadgeUserAchievement::where("user_id",$user_id)
                                    ->join("badges", "badges.id","=", "badge_user_achievements.badge_id")
                                    ->first();
    }

    /**
     * get if user has a specific badge
     *
     * @param  mixed $user_id
     * @param  mixed $badge_id
     * @return void
     */
    public function userHasAchievement($user_id, $badge_id)
    {
        return BadgeUserAchievement::where("user_id", $user_id)->where("badge_id", $badge_id)->count();
    }

    /**
     * assign a badge to a user
     *
     * @param  mixed $user_id
     * @param  mixed $badge_id
     * @return void
     */
    public function setUserBadgeAchievement($user_id, $badge_id) {
        return BadgeUserAchievement::create(["user_id" => $user_id, "badge_id" => $badge_id]);
    }
}
