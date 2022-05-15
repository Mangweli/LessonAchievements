<?php

namespace App\Models\Achievements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonWatchedAchievement extends Model
{
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
