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
        Schema::create('stockmovements', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->text('description');
            $table->string('type'); //MS = Movimiento de Stock, AS = Ajuste de Stock
            $table->string('status', 1)->default('C'); //C = Confirmado, P = Pendiente, A = Anulado
            $table->foreignId('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('stockmovements');
    }
};