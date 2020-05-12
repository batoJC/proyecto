<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaInteresesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla_intereses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('periodo');
            $table->date('fecha_vigencia_inicio');
            $table->date('fecha_vigencia_fin');
            $table->string('numero_resolucion');
            $table->decimal('tasa_efectiva_anual');
            $table->decimal('tasa_efectiva_anual_mora');
            $table->double('tasa_mora_nominal_anual')->nullable();
            $table->double('tasa_mora_nominal_mensual')->nullable();
            $table->double('tasa_diaria', 10, 9)->nullable();
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
        Schema::dropIfExists('tabla_intereses');
    }
}
