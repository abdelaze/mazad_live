<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mazdat_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mazdat_id');
            $table->foreign('mazdat_id')->references('id')->on('mazdats')->onDelete('cascade');
            $table->string('video');
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
        Schema::dropIfExists('mazdat_videos');
    }
};
