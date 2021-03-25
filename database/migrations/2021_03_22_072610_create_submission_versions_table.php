<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_versions', function (Blueprint $table) {
            $table->id();
            $table->json('submission_items');
            $table->foreignId('previous_submission_version_id')->nullable();
            $table->foreignId('submission_id');
            $table->foreignId('user_id');
            $table->boolean('current');
            $table->boolean('first');
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
        Schema::dropIfExists('submission_versions');
    }
}
