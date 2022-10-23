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
            $table->string('telefono');
            $table->string('email');
            $table->string('logo')->nullable();
            //TO DO - Agregar campos para configurar el sistema con facturación electrónica
            $table->foreignId('business_id')->references('id')->on('business');
            $table->foreignId('branch_id')->references('id')->on('branches');
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