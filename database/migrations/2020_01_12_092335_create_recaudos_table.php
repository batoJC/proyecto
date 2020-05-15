<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recaudos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->boolean('pronto_pago')->default(false);
            $table->string('consecutivo');
            $table->decimal('valor', 12, 2);
            $table->decimal('saldo_favor', 12, 2);
            $table->enum('tipo_de_pago', 
            [   
                'Pago por Transferencia Bancaria',
                'Pago en Efectivo',
                'Consignación'
            ]);
            $table->string('banco')->nullable();

            $table->boolean('anulada')->default(false);
            $table->date('fecha_anulacion')->nullable();
            $table->text('motivo')->nullable();

            // FK with recaudo
            $table->integer('reemplazo_recaudo_id')->unsigned()->nullable();
            $table->foreign('reemplazo_recaudo_id')->references('id')->on('recaudos')->onDelete('set null');

            // FK with cuenta cobro
            $table->integer('cuenta_cobro_id')->unsigned()->nullable();
            $table->foreign('cuenta_cobro_id')->references('id')->on('cuenta_cobros')->onDelete('cascade');

             // FK with cuenta cobro
             $table->integer('propietario_id')->unsigned()->nullable();
             $table->foreign('propietario_id')->references('id')->on('users');


             // FK with conjunto
             $table->integer('conjunto_id')->unsigned();
             $table->foreign('conjunto_id')->references('id')->on('conjuntos');

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
        Schema::dropIfExists('recaudos');
    }
}
