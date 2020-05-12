<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarterasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carteras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prefijo');
            $table->bigInteger('numero');
            $table->date('fecha');
            $table->decimal('valor', 12, 2);
            $table->enum('tipo_de_pago', 
            [   
                'Descuento Intereses',
                'Pago por Transferencia Bancaria',
                'Pago en Efectivo',
                'Consignación'
            ]);

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

            // $table->tinyInteger('tipo_de_movimiento');
            $table->integer('movimiento')->unsigned()->nullable();
            // Presupuesto individual al que se le aplica
            $table->integer('presupuesto_individual_id')->unsigned()->nullable();
            $table->foreign('presupuesto_individual_id')->references('id')->on('ejecucion_presupuestal_individual')->onDelete('set null');
            
            // Usuario que realiza la creación
            $table->integer('user_register_id')->unsigned()->nullable();
            $table->foreign('user_register_id')->references('id')->on('users')->onDelete('set null');

            // Usuario dueño del movimiento
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // $table->decimal('interes', 12, 2);
            // FK with tipo de unidad
            $table->integer('unidad_id')->unsigned()->nullable();
            $table->foreign('unidad_id')->references('id')->on('unidads');
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
        Schema::dropIfExists('carteras');
    }
}
