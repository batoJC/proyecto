<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('ubicacion')->nullable();
            $table->text('descripcion');
            $table->string('condicion', 128)->nullable();
            $table->double('valor', 12, 2);
            $table->boolean('garantia')->default(false);
            $table->date('valido_hasta')->nullable();
            $table->date('fecha_compra')->nullable();
            $table->string('fabricante', 80)->nullable();
            $table->string('estilo', 80)->nullable();
            $table->string('numero_serie', 80)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('foto')->nullable();

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
        Schema::dropIfExists('inventarios');
    }
}
