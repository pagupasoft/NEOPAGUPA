<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleAnalisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_analisis', function (Blueprint $table) {
            $table->id('detalle_id'); 
            $table->bigInteger('producto_id');
            $table->foreign('producto_id')->references('producto_id')->on('producto'); 
            $table->bigInteger('analisis_laboratorio_id');
            $table->foreign('analisis_laboratorio_id')->references('analisis_laboratorio_id')->on('analisis_laboratorio');   
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
        Schema::dropIfExists('detalle_analisis');
    }
}
