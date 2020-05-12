<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaldoInicialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldo_inicials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('concepto');
            $table->double('valor', 12, 2);
            $table->date('vigencia_inicio');
            $table->date('vigencia_fin')->nullable();
            $table->boolean('interes');
            $table->enum('estado',['Pago','No pago','Pronto pago'])->default('No pago');


            // Presupuesto del que se carga
            $table->integer('presupuesto_cargar_id')->unsigned()->nullable();
            $table->foreign('presupuesto_cargar_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('set null');

            // Unidad a la que pertenece
            $table->integer('unidad_id')->unsigned();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');

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
        Schema::dropIfExists('saldo_inicials');
    }
}
