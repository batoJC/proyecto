<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJornadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jornadas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->time('entrada');
            $table->time('salida');
            $table->double('HOD',3,1)->default(0,0);
            $table->double('HON',3,1)->default(0,0);
            $table->double('HODF',3,1)->default(0,0);
            $table->double('HONF',3,1)->default(0,0);
            $table->double('HEDO',3,1)->default(0,0);
            $table->double('HENO',3,1)->default(0,0);
            $table->double('HEDF',3,1)->default(0,0);
            $table->double('HENF',3,1)->default(0,0);
            // Empleado
            // *************************
            $table->integer('empleado_conjunto_id')->unsigned();
            $table->foreign('empleado_conjunto_id')->references('id')->on('empleados_conjuntos')->onDelete('cascade');
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
        Schema::dropIfExists('jornadas');
    }
}
