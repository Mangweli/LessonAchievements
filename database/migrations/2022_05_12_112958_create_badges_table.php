<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('number_of_achievements');
        });

        DB::Table('badges')
            ->insert([
                        ['name' => 'Beginner', 'number_of_achievements' => 0],
                        ['name' => 'Intermediate', 'number_of_achievements' => 4],
                        ['name' => 'Advanced', 'number_of_achievements' => 8],
                        ['name' => 'Master', 'number_of_achievements' => 10],
                    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('badges');
    }
}
