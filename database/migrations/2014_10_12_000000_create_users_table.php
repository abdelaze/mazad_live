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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('user_name');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->boolean('isVerified')->default(false);
            $table->string('photo')->default("def.jpeg");
            $table->tinyInteger('gender')->nullable();
            $table->text('fcm_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
