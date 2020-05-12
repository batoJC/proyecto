<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentaBancariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_bancarias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('banco');
            $table->string('nro_cuenta');
            $table->string('tipo');
            
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
        Schema::dropIfExists('cuenta_bancarias');
    }
}
