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
        Schema::create('stockproducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('business_id')->references('id')->on('business')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('min_quantity')->nullable();
            $table->integer('max_quantity')->nullable();
            $table->integer('alert_quantity')->nullable();
            $table->integer('purchase_price')->nullable();
            $table->integer('sale_price')->nullable();
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
        Schema::dropIfExists('stockproducts');
    }
};
