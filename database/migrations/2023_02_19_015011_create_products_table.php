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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->unsignedBigInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onUpdate('cascade');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade');
            $table->foreignId('city_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade');
            $table->string('product_name' ,300);
            $table->text('product_desc');
            $table->dateTime('end_date');            
            $table->double('price', 15, 8)->default(0.00);
            $table->string('currency');
            $table->tinyInteger('is_used')->default(0);
            $table->tinyInteger('is_sold')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->Integer('views')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
