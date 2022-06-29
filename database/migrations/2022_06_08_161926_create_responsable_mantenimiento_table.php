<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsableMantenimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsable_mantenimiento', function (Blueprint $table) {
            $table->id('responsable_id');
            $table->integer('responsable_estado');

            $table->bigInteger('orden_id');
            $table->foreign('orden_id')->references('orden_id')->on('orden_mantenimiento');

            $table->bigInteger('empleado_id');
            $table->foreign('empleado_id')->references('empleado_id')->on('empleado');

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
        Schema::dropIfExists('responsable_mantenimiento');
    }
}
