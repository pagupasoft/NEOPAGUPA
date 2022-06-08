<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleMantenimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_mantenimiento', function (Blueprint $table) {
            $table->id('detalle_id');
            $table->date('detalle_fecha_inicio');
            $table->date('detalle_fecha_fin')->nullable();
            $table->string('detalle_descripcion');
            $table->string('detalle_estado');
            $table->bigInteger('orden_id');
            $table->foreign('orden_id')->references('orden_id')->on('orden_mantenimiento');
            $table->bigInteger('diario_id')->nullable();
            $table->foreign('diario_id')->references('diario_id')->on('diario');
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
        Schema::dropIfExists('detalle_mantenimiento');
    }
}
