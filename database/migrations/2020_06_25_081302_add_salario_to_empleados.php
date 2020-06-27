<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalarioToEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('empleados_conjuntos', function (Blueprint $table) {
            $table->double('salario', 12,2)->nullable()->after('cedula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('empleados_conjuntos', function (Blueprint $table) {
            $table->dropColumn('salario');
        });
    }
}
