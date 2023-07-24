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
        Schema::create('stockmovementdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stockmovement_id')->references('id')->on('stockmovements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('initialbranch_id')->nullable()->references('id')->on('branches');
            $table->foreignId('finalbranch_id')->nullable()->references('id')->on('branches');
            $table->integer('quantity');
            $table->foreignId('business_id')->references('id')->on('business');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stockmovementdetails');
    }
};