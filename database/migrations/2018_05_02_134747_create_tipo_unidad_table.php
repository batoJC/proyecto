<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoUnidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_unidad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',30);
            // Conjunto
            $table->integer('conjunto_id')->unsigned();
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('cascade');
            //unique doble
            $table->unique(['nombre', 'conjunto_id']);
            
            // $table->string('numero_letra');
            // $table->decimal('coeficiente')->nullable();
            // $table->integer('tipo_unidad_id');
            // // Bloque, Torre 
            // $table->integer('division_id')->unsigned()->nullable();
            // $table->foreign('division_id')->references('id')->on('divisiones')->onDelete('cascade');
            // // Parqueadero
            // $table->integer('tipo_unidad_id')->unsigned()->nullable();
            // $table->foreign('tipo_unidad_id')->references('id')->on('parqueaderos');
            // // Conjunto
            // $table->integer('id_conjunto')->unsigned();
            // $table->foreign('id_conjunto')->references('id')->on('conjuntos')->onDelete('cascade');
            // // Dueño del Apto
            // $table->integer('id_dueno_apto')->unsigned();
            // $table->foreign('id_dueno_apto')->references('id')->on('users');
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
        Schema::dropIfExists('tipo_unidad');
    }
}
