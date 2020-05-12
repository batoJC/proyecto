<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_completo');
            $table->string('email');
            $table->string('documento');
            $table->string('direccion');
            $table->bigInteger('telefono')->nullable();
            $table->bigInteger('celular');

            //tipo de documento
            /*****************/
            $table->integer('tipo_documento')->unsigned();
            $table->foreign('tipo_documento')->references('id')->on('tipo_documentos');

            
            // Conjunto al que pertenece
            // *************************
            $table->integer('conjunto_id')->unsigned();
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('cascade');

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
        Schema::dropIfExists('proveedors');
    }
}
