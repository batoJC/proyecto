<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEjecucionPresupuestalIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ejecucion_presupuestal_individual', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('total');
            $table->text('soportes')->nullable();
            // Tipo de ejecucion
            $table->integer('id_tipo_ejecucion')->unsigned();
            $table->foreign('id_tipo_ejecucion')->references('id')->on('tipo_ejecucion_pre')->onDelete('cascade');
            // Ejecución presupuestal total
            $table->integer('id_ejecucion_pre_total')->unsigned();
            $table->foreign('id_ejecucion_pre_total')->references('id')->on('ejecucion_presupuestal_total')->onDelete('cascade');
            // Conjunto al que pertence
            $table->integer('conjunto_id')->unsigned()->nullable();
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('set null');
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
        Schema::dropIfExists('ejecucion_presupuestal_individual');
    }
}
