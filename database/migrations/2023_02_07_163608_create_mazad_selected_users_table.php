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
        Schema::create('mazad_selected_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mazdat_id');
            $table->foreign('mazdat_id')->references('id')->on('mazdats')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('price', 15, 8)->default(0.00);
            $table->string('currency');
            $table->string('payment_status')->default('pending');
            $table->string('payment_id')->nullable();
            $table->Date('paid_date_to_owner')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->double('paid_amount', 15, 8)->default(0.00);
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
        Schema::dropIfExists('mazad_selected_users');
    }
};
