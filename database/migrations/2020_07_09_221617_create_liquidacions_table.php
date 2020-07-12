<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiquidacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->string('consecutivo',25)->unique();
            $table->string('periodo',25);
            $table->double('salario',12,2);
            $table->double('subsidio_transporte',12,2);
            $table->integer('dias_transporte')->default(0);
            // Empleado
            // *************************
            $table->integer('empleado_conjunto_id')->unsigned();
            $table->foreign('empleado_conjunto_id')->references('id')->on('empleados_conjuntos')->onDelete('cascade');
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
        Schema::dropIfExists('liquidaciones');
    }
}
