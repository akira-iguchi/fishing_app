<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotFishingTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spot_fishing_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spot_id');
            $table->foreign('spot_id')
                ->references('id')
                ->on('spots')
                ->onDelete('cascade');
            $table->unsignedBigInteger('fishing_type_id');
            $table->foreign('fishing_type_id')
                ->references('id')
                ->on('fishing_types')
                ->onDelete('cascade');
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
        Schema::dropIfExists('spot_fishing_type');
    }
}
