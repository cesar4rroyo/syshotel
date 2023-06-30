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
            $table->string('number')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('A'); // A = Active, I = Inactive, D = Deleted
            $table->text('image')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('comment')->nullable();
            $table->foreignId('concept_id')->references('id')->on('concepts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('payment_id')->references('id')->on('paymenttypes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('card_id')->nullable()->references('id')->on('cards')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('digitalwallet_id')->nullable()->references('id')->on('digitalwallets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('pos_id')->nullable()->references('id')->on('pos')->onUpdate('cascade')->onDelete('cascade');
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
