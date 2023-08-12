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
        Schema::create('productinrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->references('id')->on('services')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('room_id')->references('id')->on('rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('process_id')->references('id')->on('processes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('business_id')->references('id')->on('business')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('purchase_price', 8, 2);
            $table->decimal('sale_price', 8, 2);
            $table->decimal('total_purchase_price', 8, 2);
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
        Schema::dropIfExists('productinrooms');
    }
};
