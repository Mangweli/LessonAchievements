<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonWatchedAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_watched_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number_of_lessons');
        });

        DB::Table('lesson_watched_achievements')
            ->insert([
                        ['name' => 'First Lesson Watched', 'number_of_lessons' => 1],
                        ['name' => '5 Lessons Watched', 'number_of_lessons' => 5],
                        ['name' => '10 Lessons Watched', 'number_of_lessons' => 10],
                        ['name' => '25 Lessons Watched', 'number_of_lessons' => 25],
                        ['name' => '50 Lessons Watched', 'number_of_lessons' => 50]
                    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_watched_achievements');
    }
}
