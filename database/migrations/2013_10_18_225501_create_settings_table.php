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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('nombre_comercial');
            $table->string('ruc');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('checkin')->nullable();
            $table->string('checkout')->nullable();
            //TO DO - Agregar campos para configurar el sistema con facturación electrónica
            $table->boolean('has_electronic_billing')->nullable()->default(false);
            $table->string('igv')->nullable()->default('10');
            $table->string('serie')->nullable();
            $table->foreignId('business_id')->references('id')->on('business');
            $table->foreignId('branch_id')->references('id')->on('branches');
            $table->string('password_sunnat')->nullable();
            $table->integer('serverId')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
