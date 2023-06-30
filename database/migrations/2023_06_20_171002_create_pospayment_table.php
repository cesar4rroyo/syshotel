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
        Schema::create('pospayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id')->references('id')->on('pos');
            $table->foreignId('paymenttype_id')->references('id')->on('paymenttypes');
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
        Schema::dropIfExists('pospayments');
    }
};
