<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spot_comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->string('comment_image')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('spot_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->foreign('spot_id')->references('id')->on('spots')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spot_comments');
    }
}
