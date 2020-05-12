<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExcluirPresupuesto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('excluir_presupuesto', function (Blueprint $table) {
            $table->integer('presupuesto_id')->unsigned();
            $table->integer('unidad_id')->unsigned();


            $table->foreign('unidad_id')->references('id')->on('unidads')
                ->onDelete('cascade');
            $table->foreign('presupuesto_id')->references('id')->on('ejecucion_presupuestal_individual')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('excluir_presupuesto');
    }
}
