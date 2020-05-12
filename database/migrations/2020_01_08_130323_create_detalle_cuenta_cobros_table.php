<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleCuentaCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_cuenta_cobros', function (Blueprint $table) {
            $table->increments('id');
            $table->date('vigencia_inicio');
            $table->date('vigencia_fin')->nullable();
            $table->string('referencia')->nullable();
            $table->string('concepto');
            $table->double('valor',12,2);
            $table->double('interes',12,2);
            $table->enum('tipo',['administracion','extraordinaria','otro_cobro','multa','saldo_inicial']);
            $table->integer('cuota_id');

            // FK with cuenta cobro
            $table->integer('cobro_id')->unsigned();
            $table->foreign('cobro_id')->references('id')->on('cuenta_cobros')->onDelete('cascade');

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
        Schema::dropIfExists('detalle_cuenta_cobros');
    }
}
