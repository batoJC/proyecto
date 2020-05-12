<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_egresos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('concepto');
            $table->double('valor',12,2);
            // presupuesto individual
            // *************************
            $table->integer('presupuesto_id')->unsigned()->nullable();
            $table->foreign('presupuesto_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('set null');
            // egreso
            // *************************
            $table->integer('egreso_id')->unsigned();
            $table->foreign('egreso_id')->references('id')->on('egresos')->onDelete('cascade');
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
        Schema::dropIfExists('detalle_egresos');
    }
}
