<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCommentAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment_written_achievement_id',
        'comment_id'
    ];
}
