<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_completo');
            $table->string('direccion',100);
            $table->string('ocupacion',100)->nullable();
            $table->string('numero_cedula', 125);
            $table->string('email', 125)->unique();
            $table->string('password');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero')->nullable();
            $table->bigInteger('telefono')->nullable();
            $table->bigInteger('celular');
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('habeas_data')->default('Sin Aceptar');
            // Rol al que pertenece
            // *************************
            $table->integer('id_rol')->unsigned();
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
            // Conjunto al que pertenece
            // *************************
            $table->integer('id_conjunto')->unsigned()->nullable();
            $table->foreign('id_conjunto')->references('id')->on('conjuntos')->onDelete('cascade');
            //tipo de documento
            /*****************/
            $table->integer('tipo_documento')->unsigned();
            $table->foreign('tipo_documento')->references('id')->on('tipo_documentos');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
