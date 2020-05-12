<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotaAdmExOrdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuota_adm_ex_ord', function (Blueprint $table) {
            $table->increments('id');
            $table->string('concepto',128);
            $table->date('vigencia_inicio');
            $table->date('vigencia_fin');
            $table->boolean('interes');

            // Presupuesto del que se calcula
            $table->integer('presupuesto_cargar_id')->unsigned()->nullable();
            $table->foreign('presupuesto_cargar_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('cascade');

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
        Schema::dropIfExists('cuota_adm_ex_ord');
    }
}
