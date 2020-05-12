<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuejasReclamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quejas_reclamos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo');
            $table->text('peticion');
            $table->text('hechos');
            $table->string('estado');
            $table->string('archivo')->nullable();
            $table->date('fecha_solicitud');
            $table->text('respuesta')->nullable();
            $table->date('fecha_respuesta')->nullable();
            $table->bigInteger('dias_restantes');
            // Creador de la queja / sugerencia
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            // Creador del proveedor que la resuelve
            $table->integer('id_proveedor')->unsigned()->nullable();
            $table->foreign('id_proveedor')->references('id')->on('users');
            // *********************
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
        Schema::dropIfExists('quejas_reclamos');
    }

}
