<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoLiquidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->enum('tipo',['liquidacion','prestaciones'])->default('liquidacion')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}
