<?php

namespace App\Interfaces;

interface BadgeRepositoryInterface {
    public function getUserNextBadgeAchievement(int $user_id);
    public function getBadgeAchievement(int $achievementNumber);
    public function getUserCurrentBadge(int $user_id);
    public function userHasAchievement(int $user_id, int $achievement_id);
    public function setUserBadgeAchievement(int $user_id, int $badge_id);
}
