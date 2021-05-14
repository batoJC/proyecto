<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroFallosCargaUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_fallos_carga_unidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registro');
            $table->string('descripcion_fallo');            
            $table->integer('archivo_masivo_id')->unsigned();                        
            $table->timestamps();
            //llaves foraneas

            $table->foreign('archivo_masivo_id')->references('id')->on('archivo_carga_masivas')->onDelete('cascade');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro_fallos_carga_unidades');
    }
}
