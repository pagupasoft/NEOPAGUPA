<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleAnalisisValoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_analisis_valores', function (Blueprint $table) {
            $table->id('detalle_valores_id');
            $table->string('detalle_valor');
            $table->string('detalle_unidad')->nullable();
            $table->string('detalle_estado');
            $table->bigInteger('detalle_id');
            $table->foreign('detalle_id')->references('detalle_id')->on('detalle_analisis'); 
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
        Schema::dropIfExists('detalle_analisis_valores');
    }
}
