<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaCreditoBancoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_credito_banco', function (Blueprint $table) {
            $table->id('nota_id');
            $table->string('nota_numero')->unique();
            $table->string('nota_serie');
            $table->float('nota_secuencial');
            $table->date('nota_fecha'); 
            $table->double('nota_valor');
            $table->string('nota_descripcion');
            $table->string('nota_beneficiario');   
            $table->string('nota_estado');     
            $table->bigInteger('rango_id');
            $table->foreign('rango_id')->references('rango_id')->on('rango_documento'); 
            $table->bigInteger('diario_id');
            $table->foreign('diario_id')->references('diario_id')->on('diario'); 
            $table->bigInteger('cuenta_bancaria_id');
            $table->foreign('cuenta_bancaria_id')->references('cuenta_bancaria_id')->on('cuenta_bancaria');          
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
        Schema::dropIfExists('nota_credito_banco');
    }
}
