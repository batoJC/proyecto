<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoCargaMasivasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_carga_masivas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ruta');
            $table->string('email')->default("");
            $table->string('nombre_archivo');
            $table->integer('fallos');
            $table->integer('procesados');
            $table->enum('estado',['subido','en progreso','terminado','eliminado']);
            $table->integer('tipo_unidad_id')->unsigned();
            $table->integer('conjunto_id')->unsigned();
            $table->integer('indice_unidad');
            $table->integer('indice_mascotas');
            $table->integer('indice_residentes');
            $table->integer('indice_vehiculos');
            $table->integer('indice_empleados');
            $table->integer('indice_visitantes');
            $table->timestamps();

            //llaves foraneas config
            $table->unique(array('nombre_archivo', 'conjunto_id'));
            $table->foreign('conjunto_id')->references('id')->on('conjuntos')->onDelete('cascade');
            $table->foreign('tipo_unidad_id')->references('id')->on('tipo_unidad')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivo_carga_masivas');
    }
}
