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
        DB::table('variables')->truncate();//delete all register old
        DB::table('variables')->insert([
            [
                'name' => 'horas_jornada',
                'descripcion' => 'Jornada máxima legal mensual en horas.',
                'modulo' => 'liquidacion',
                'value' => 240
            ],
            [
                'name' => 'jornada_ordinaria',
                'descripcion' => 'Horas de la jornada ordinaria.',
                'modulo' => 'liquidacion',
                'value' => 8
            ],
            [
                'name' => 'inicio_jornada',
                'descripcion' => 'Hora de inicio de la jornada ordinaria (Formato militar).',
                'modulo' => 'liquidacion',
                'value' => '6:00'
            ],
            [
                'name' => 'final_jornada',
                'descripcion' => 'Hora de final de la jornada ordinaria (Formato militar).',
                'modulo' => 'liquidacion',
                'value' => '21:00'
            ],
            [
                'name' => 'recargo_ordinario_nocturno',
                'descripcion' => 'Porcentaje del recargo nocturno.',
                'modulo' => 'liquidacion',
                'value' => 35
            ],
            [
                'name' => 'recargo_ordinario_diurno_festivo',
                'descripcion' => 'Porcentaje del recargo diurno festivo.',
                'modulo' => 'liquidacion',
                'value' => 75
            ],
            [
                'name' => 'recargo_ordinario_nocturno_festivo',
                'descripcion' => 'Porcentaje del recargo nocturno festivo.',
                'modulo' => 'liquidacion',
                'value' => 75
            ],
            [
                'name' => 'hora_extra_ordinaria_diurna',
                'descripcion' => 'Tarifa de la hora extra ordinaria diurna.',
                'modulo' => 'liquidacion',
                'value' => 1.25
            ],
            [
                'name' => 'hora_extra_ordinaria_nocturna',
                'descripcion' => 'Tarifa de la hora extra ordinaria nocturna.',
                'modulo' => 'liquidacion',
                'value' => 1.75
            ],
            [
                'name' => 'hora_extra_ordinaria_diurna_fesiva',
                'descripcion' => 'Tarifa de la hora extra diurna festiva.',
                'modulo' => 'liquidacion',
                'value' => 2.00
            ],
            [
                'name' => 'hora_extra_ordinaria_nocturna_festiva',
                'descripcion' => 'Tariafa de la hora extra nocturna festiva.',
                'modulo' => 'liquidacion',
                'value' => 2.50
            ],
            [
                'name' => 'subsidio_transporte',
                'descripcion' => 'Es el valor del subsidio de transporte.',
                'modulo' => 'liquidacion',
                'value' => 102854
            ],
            [
                'name' => 'interes_cesantias',
                'descripcion' => 'Es el valor sobre el que se calcula el interés a las cesantías.',
                'modulo' => 'liquidacion',
                'value' => 0.12
            ]
        ]);
    }
}
