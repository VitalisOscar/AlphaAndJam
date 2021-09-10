<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaybackCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playback_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screen_id')->constrained('screens');
            $table->foreignId('package_id')->constrained('packages');
            $table->date('play_date');
            $table->foreignId('staff_id')->constrained('staff');
            $table->string('comment');
            $table->timestamps();

            $table->unique(['screen_id', 'package_id', 'play_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playback_comments');
    }
}
