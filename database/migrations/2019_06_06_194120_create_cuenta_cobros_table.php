<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentaCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_cobros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('consecutivo');
            $table->decimal('saldo_favor', 12, 2);
            $table->date('fecha');
            $table->enum('tipo_cobro',['normal','pre-juridico','juridico'])->default('normal');
            $table->date('fecha_pronto_pago')->nullable();
            $table->boolean('anulada')->default(false);
            $table->text('motivo')->nullable();
            $table->date('fecha_anulado')->nullable();
            // FK with recaudo
            $table->integer('reemplazo_cuenta_id')->unsigned()->nullable();
            $table->foreign('reemplazo_cuenta_id')->references('id')->on('cuenta_cobros')->onDelete('set null');

            $table->double('descuento',6,4)->default(0);
            
            // FK with propietario
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
        Schema::dropIfExists('cuenta_cobros');
    }
}
