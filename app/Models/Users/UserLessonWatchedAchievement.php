<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLessonWatchedAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_achievement_id',
        'lesson_id'
    ];
}
