<?php

namespace Database\Factories\Users;

use App\Models\Achievements\CommentWrittenAchievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCommentAchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'comment_written_achievement_id' => CommentWrittenAchievement::factory(),
            'comment_id' => Comment::factory()
        ];
    }
}
