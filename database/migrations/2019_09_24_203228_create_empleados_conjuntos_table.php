<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosConjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados_conjuntos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_completo');
            $table->string('cedula',60);
            $table->string('direccion');
            $table->string('cargo');
            //estado
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('foto')->nullable();
            // Conjunto al que pertenece
            // *************************
            $table->integer('conjunto_id')->unsigned();
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('cascade');

            //unique multiple
            $table->unique(['cedula','conjunto_id'],'empleado_unico');
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
        Schema::dropIfExists('empleados_conjuntos');
    }
}
