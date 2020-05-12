<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraordinariaUnidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('extraordinaria_unidades', function (Blueprint $table) {

            $table->double('valor', 12, 2);
            $table->enum('estado', ['Pago','No pago','Pronto pago'])->default('No pago');

            // cuota de administracion
            $table->integer('cuota_id')->unsigned();
            $table->foreign('cuota_id')->references('id')->on('cuota_adm_ex_ord')->onDelete('cascade');

            // unidad
            $table->integer('unidad_id')->unsigned();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('extraordinaria_unidades');
    }
}
