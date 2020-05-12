<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residentes', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo_residente', ['inquilino', 'familiar', 'propietario']);
            $table->string('nombre');
            $table->string('apellido');
            $table->string('profesion')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero');
            $table->string('documento', 125);
            // $table->enum('generar_carta', ['Si', 'No'])->default('No');
            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');

            //carta ingreso
            $table->integer('carta_ingreso_id')->unsigned()->nullable();
            $table->foreign('carta_ingreso_id')->references('id')->on('cartas');

            //carta retiro
            $table->integer('carta_retiro_id')->unsigned()->nullable();
            $table->foreign('carta_retiro_id')->references('id')->on('cartas');

            // unidad a la que pertenece
            // ********************************
            $table->integer('unidad_id')->unsigned();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');
             // Tipo de documento 
            // ********************************
            $table->integer('tipo_documento_id')->unsigned();
            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documentos');
            // Conjunto al que pertenece
            // *************************
            $table->integer('id_conjunto')->unsigned();
            $table->foreign('id_conjunto')->references('id')->on('conjuntos')->onDelete('cascade');
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
        Schema::dropIfExists('residentes');
    }
}
