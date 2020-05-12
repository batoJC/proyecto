<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantenimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->text('descripcion');
            $table->string('archivo')->nullable();
            $table->boolean('realizado')->default(false);

            // conjunto a la que pertenece
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
        Schema::dropIfExists('mantenimientos');
    }
}
