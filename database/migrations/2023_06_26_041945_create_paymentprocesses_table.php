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
        Schema::create('paymentprocesses', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('number');
            $table->string('description');
            $table->string('status')->default('A'); // A = Active, I = Inactive, D = Deleted
            $table->text('image')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('comment')->nullable();
            $table->foreignId('card_id')->references('id')->on('cards')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignId('bank_id')->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignId('digitalwallet_id')->references('id')->on('digitalwallets')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignId('pos_id')->references('id')->on('pos')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignId('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('business_id')->references('id')->on('business')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('paymentprocesses');
    }
};
