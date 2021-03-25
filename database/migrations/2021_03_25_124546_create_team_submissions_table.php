<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_submissions', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description');
            $table->foreignId('user_id');
            $table->text('story_concept_files')->nullable();
            $table->text('summary_files')->nullable();
            $table->text('character_design')->nullable();
            $table->text('world_design')->nullable();
            $table->text('team_profile');
            $table->text('pilot_video')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_submissions');
    }
}
