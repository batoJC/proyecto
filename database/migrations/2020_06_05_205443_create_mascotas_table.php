<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMascotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo')->unsigned()->nullable();
            $table->string('nombre')->nullable();
            $table->string('raza',60)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('foto')->nullable();
            //estado
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');

            //carta ingreso
            $table->integer('carta_ingreso_id')->unsigned()->nullable();
            $table->foreign('carta_ingreso_id')->references('id')->on('cartas');

            //carta retiro
            $table->integer('carta_retiro_id')->unsigned()->nullable();
            $table->foreign('carta_retiro_id')->references('id')->on('cartas');

            //tipo 
            $table->integer('tipo_id')->unsigned();
            $table->foreign('tipo_id')->references('id')->on('tipo_mascotas')->onDelete('cascade');

            //unidad a la que pertenece
            $table->integer('unidad_id')->unsigned();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');

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
        Schema::dropIfExists('mascotas');
    }
}
