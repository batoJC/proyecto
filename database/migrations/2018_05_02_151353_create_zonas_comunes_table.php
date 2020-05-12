<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonasComunesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zonas_comunes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->bigInteger('valor_uso')->nullable();
            $table->integer('numero');
            $table->string('tipo',10);
            
            // Id del conjunto al cual le pertenece
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
        Schema::dropIfExists('zonas_comunes');
    }
}
