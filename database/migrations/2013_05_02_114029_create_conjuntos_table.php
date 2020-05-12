<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conjuntos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit');
            $table->string('nombre');
            $table->string('correo',80);
            $table->string('password',256)->nullable();
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('barrio');
            $table->string('tel_cel')->nullable();
            $table->string('logo')->nullable();
            $table->integer('id_tipo_propiedad')->unsigned();
            $table->foreign('id_tipo_propiedad')->references('id')->on('tipo_conjunto');
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
        Schema::dropIfExists('conjuntos');
    }
}
