<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeduccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduccions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion',80);
            $table->integer('descuento')->nullable();
            $table->double('valor',12,2);
            // liquidación
            // *************************
            $table->integer('liquidacion_id')->unsigned();
            $table->foreign('liquidacion_id')->references('id')->on('liquidaciones')->onDelete('cascade');
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
        Schema::dropIfExists('deduccions');
    }
}
