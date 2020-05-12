<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_recaudos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('concepto');
            $table->decimal('valor', 12, 2);
            $table->enum('tipo_de_cuota', 
            [   
                'Cuota Administrativa', 
                'Cuota Extraordinaria',
                'Otro Cobro',
                'Multa',
                'Saldo Inicial',
                'Interes Cuota Administrativa', 
                'Interes Cuota Extraordinaria',
                'Interes Otro Cobro',
                'Interes Multa',
                'Interes Saldo Inicial'
            ]);
                
            $table->string('cuenta_id');

            // Unidad a la que pertenece
            $table->integer('unidad_id')->unsigned()->nullable();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('set null');

            // Presupuesto del que se carga
            $table->integer('presupuesto_id')->unsigned()->nullable();
            $table->foreign('presupuesto_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('set null');

             // FK with conjunto
             $table->integer('recaudo_id')->unsigned();
             $table->foreign('recaudo_id')->references('id')->on('recaudos')->onDelete('cascade');
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
        Schema::dropIfExists('detalle_recaudos');
    }
}
