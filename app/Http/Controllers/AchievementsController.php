<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
       // dd($user);

       //dd(Comment::create(['body' => 'Test', 'user_id' => $user->id]));
       //$comment = Comment::where('id', 1)->first();
        $lesson = Lesson::where('id', 5)->first();

       //dd($comment);
       // event(new CommentWritten(Comment::create(['body' => 'Test 3', 'user_id' => $user->id])));
        event(new LessonWatched($lesson, $user));
       // event(new CommentWritten($comment));

        return response()->json([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }
}
