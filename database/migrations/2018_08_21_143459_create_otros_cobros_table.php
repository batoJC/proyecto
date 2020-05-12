<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtrosCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otros_cobros', function (Blueprint $table) {
            $table->increments('id');
            $table->double('valor', 12, 2);
            $table->date('vigencia_inicio');
            $table->date('vigencia_fin')->nullable();
            $table->enum('estado',['Pago','No pago','Pronto pago'])->default('No pago');
            $table->boolean('interes');
            $table->text('descripcion')->nullable();
            $table->string('concepto');

            // unidad
            $table->integer('unidad_id')->unsigned()->nullable();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');

            // Presupuesto del que se calcula
            $table->integer('presupuesto_cargar_id')->unsigned()->nullable();
            $table->foreign('presupuesto_cargar_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('set null');

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
        Schema::dropIfExists('otros_cobros');
    }
}
