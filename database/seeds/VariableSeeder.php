<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('variables')->insert([
            [ 'name' => 'salario','value' => 993800],
            [ 'name' => 'horas_jornada','value' => 240],
            [ 'name' => 'jornada_ordinaria','value' => 8],
            [ 'name' => 'inicio_jornada','value' => '6:00'],
            [ 'name' => 'final_jornada','value' => '21:00'],
            [ 'name' => 'recargo_ordinario_nocturno','value' => 35],
            [ 'name' => 'recargo_ordinario_diurno_festivo','value' => 75],
            [ 'name' => 'recargo_ordinario_nocturno_festivo','value' => 75],
            [ 'name' => 'hora_extra_ordinaria_diurna','value' => 1.25],
            [ 'name' => 'hora_extra_ordinaria_nocturna','value' => 1.75],
            [ 'name' => 'hora_extra_ordinaria_diurna_fesiva','value' => 2.00],
            [ 'name' => 'hora_extra_ordinaria_nocturna_festiva','value' => 2.50]
        ]);
    }
}
