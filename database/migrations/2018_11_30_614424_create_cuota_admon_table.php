<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotaAdmonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuota_admon', function (Blueprint $table) {
            $table->increments('id');
            // $table->double('valor', 10, 2);
            $table->date('vigencia_inicio');
            $table->date('vigencia_fin');
            $table->boolean('interes');
            // $table->enum('estado',['Pago','No pago'])->default('No pago');


            // Presupuesto del que se calcula
            // $table->integer('presupuesto_calcular_id')->unsigned()->nullable();
            // $table->foreign('presupuesto_calcular_id')->references('id')->on('ejecucion_presupuestal_total')->onDelete('set null');

            // Presupuesto del que se carga
            $table->integer('presupuesto_cargar_id')->unsigned()->nullable();
            $table->foreign('presupuesto_cargar_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('set null');


            // Unidad a la que pertenece
            // $table->integer('unidad_id')->unsigned();
            // $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');

            // Conjunto al que pertence
            $table->integer('conjunto_id')->unsigned();
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('cascade');

            // acta
            // $table->integer('acta_id')->unsigned()->nullable();
            // $table->foreign('acta_id')->references('id')->on('actas')->onDelete('set null');

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
        Schema::dropIfExists('cuota_admon');
    }
}
