<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spot_images', function (Blueprint $table) {
            $table->id();
            $table->string('spot_image')->default('defaultSpot.jpg');
            $table->timestamps();

            $table->unsignedBigInteger('spot_id')->default(0);
            $table->foreign('spot_id')->references('id')->on('spots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spot_images');
    }
}
