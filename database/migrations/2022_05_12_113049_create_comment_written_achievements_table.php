<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentWrittenAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_written_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('number_of_comments');
        });

        DB::Table('comment_written_achievements')
            ->insert([
                        ['name' => 'First Comment Written', 'number_of_comments' => 1],
                        ['name' => '3 Comments Written', 'number_of_comments' => 3],
                        ['name' => '5 Comments Written', 'number_of_comments' => 5],
                        ['name' => '10 Comment Written', 'number_of_comments' => 10],
                        ['name' => '20 Comment Written', 'number_of_comments' => 20]
                    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_written_achievements');
    }
}
