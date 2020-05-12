<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');

            $table->date('fecha_solicitud');
            $table->text('motivo');

            $table->integer('asistentes')->default(0);

            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');

            $table->enum('estado',['rechazada','aceptada','pendiente'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();


            //propietario que hace la reserva
            $table->integer('propietario_id')->unsigned();
            $table->foreign('propietario_id')->references('id')->on('users')->onDelete('cascade');

            //zona común a la que pertenece
            $table->integer('zona_comun_id')->unsigned();
            $table->foreign('zona_comun_id')->references('id')->on('zonas_comunes')->onDelete('cascade');

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
        Schema::dropIfExists('reservas');
    }
}
