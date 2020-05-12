<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('foto_vehiculo',40);
            $table->string('foto_tarjeta_1',40);
            $table->string('foto_tarjeta_2',40);
            $table->string('registra',60);
            $table->enum('tipo',['carro','moto','otro']);
            $table->string('marca',60);
            $table->string('color',60);
            $table->string('placa',30);

            //carta ingreso
            $table->integer('carta_ingreso_id')->unsigned()->nullable();
            $table->foreign('carta_ingreso_id')->references('id')->on('cartas');

            //carta retiro
            $table->integer('carta_retiro_id')->unsigned()->nullable();
            $table->foreign('carta_retiro_id')->references('id')->on('cartas');

            //estado
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');

            // unidad a la que pertenece
            // ********************************
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
        Schema::dropIfExists('vehiculos');
    }
}
