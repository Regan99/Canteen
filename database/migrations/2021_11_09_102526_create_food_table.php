<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_category_id');
            $table->unsignedBigInteger('school_id');
            $table->mediumText('food_name');
            $table->string('image');
            $table->string('price');
            $table->string('discount')->nullable();
            $table->enum('status',['active','inactive']);
            $table->foreign('food_category_id')->references('id')->on('food_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('school_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('food');
    }
}
