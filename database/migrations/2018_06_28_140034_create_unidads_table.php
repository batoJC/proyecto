<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_letra',30);
            $table->string('referencia',30)->nullable();
            $table->double('coeficiente',8,5)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('editar')->default(false);
            $table->boolean('cambio_propietario')->default(false);

            //Foreign Keys
            /************/

            //division a la que pertenece
            $table->integer('division_id')->unsigned();
            $table->foreign('division_id')->references('id')->on('divisiones')->onDelete('cascade');
            //conjunto al que pertenece
            $table->integer('conjunto_id')->unsigned()->nullable();
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('set null');
            //unidad a al que pertenece si aplica
            //DEPRECATED: este campo fue deprecado ya que no es necesario
            $table->integer('unidad_id')->unsigned()->nullable();
            $table->foreign('unidad_id')->references('id')->on('unidads')->onDelete('cascade');
            //tipo de unidad
            $table->integer('tipo_unidad_id')->unsigned()->nullable();
            $table->foreign('tipo_unidad_id')->references('id')->on('tipo_unidad')->onDelete('cascade');
            //propietario que paga
            $table->integer('propietario_id')->unsigned()->nullable();
            $table->foreign('propietario_id')->references('id')->on('users')->onDelete('set null');

            //unique multiple
            $table->unique(['numero_letra', 'tipo_unidad_id','conjunto_id','division_id'],'unidad_unica');

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
        Schema::dropIfExists('unidads');
    }
}
