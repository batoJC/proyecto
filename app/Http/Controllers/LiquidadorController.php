<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\EmpleadosConjunto;
use App\Feriados;
use App\Jornada;
use App\Variable;
use Illuminate\Http\Request;
use DateTime;
use Exception;

class LiquidadorController extends Controller
{
    //

    public function index(EmpleadosConjunto $empleado)
    {
        $conjunto = Conjunto::find(session('conjunto'));
        return view('admin.liquidador.index')->with('empleado', $empleado)->with('conjuntos', $conjunto);
    }

    public function getJornadas(Request $request)
    {
        try {
            return $this->crearJornadas($request->fecha_inicio, $request->fecha_fin, $request->continuo);
        } catch (Exception $th) {
            return ['res' => 0, 'msg' => 'Ocurrión un error al generar las jornadas!', 'e' => $th];
        }
    }

    public function informacion()
    {
        $datos = array();
        $datos['salario'] = 9000000;
        $datos['horas_jornada'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['jornada_ordinaria'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['inicio_jornada'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['final_jornada'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['recargo_ordinario_nocturno'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['recargo_ordinario_diurno_festivo'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['recargo_ordinario_nocturno_festivo'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['hora_extra_ordinaria_diurna'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['hora_extra_ordinaria_nocturna'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['hora_extra_ordinaria_diurna_fesiva'] = Variable::where('name', 'horas_jornada')->first()->value;
        $datos['hora_extra_ordinaria_nocturna_festiva'] = Variable::where('name', 'horas_jornada')->first()->value;


        return view('admin.liquidador.informacion')->with('datos', $datos);
    }


    public function vistaGenerar(EmpleadosConjunto $empleado)
    {
        $conjunto = Conjunto::find(session('conjunto'));

        return view('admin.liquidador.generar')
            ->with('conjuntos', $conjunto)
            ->with('empleado', $empleado);
    }


    private function crearJornadas($inicio, $fin, $continuo = true)
    {
        $jornadas = array();
        $inicio = new DateTime($inicio);
        $fin = new DateTime($fin);
        $interval = $inicio->diff($fin);


        if ($interval->invert) {
            throw new Exception("La fecha de inicio debe ser menor a la fecha de fin.");
        }

        if ($continuo) { //solo una jornada
            $hora_inicio = $inicio->format('H');
            while ($inicio != $fin) {

                $fin_aux = clone ($inicio);
                $fin_aux = $fin_aux->add(date_interval_create_from_date_string(" +1 day -{$hora_inicio} hours"));

                //verificación para que no salte al día siquiente
                $ai = clone ($inicio);
                $af = clone ($fin_aux);
                if ($ai->format('Y-m-d') != $af->format('Y-m-d')) {
                    $horas = $af->format('H');
                    $minutos = $af->format('i');
                    $fin_aux->add(date_interval_create_from_date_string("-{$horas} hours -{$minutos} minutes"));
                }

                if ($fin_aux->diff($fin)->invert) {
                    $fin_aux = $fin;
                }

                $data = $this->calcularHorasPorHorario($inicio, $fin_aux, $continuo);
                $fecha = clone ($inicio);
                $data['fecha'] = $fecha->format('d-m-Y');
                $data['entrada'] = $inicio->format('h:i A');
                $fecha = clone ($fin_aux);
                $data['salida'] = $fecha->format('h:i A');
                // $jornada = new Jornada();
                // $jornada->fillable($data);
                $jornadas[] = $data;

                $inicio = $fin_aux;
                $hora_inicio = 0;
            }
        } else { //Todos los días un mismo horario
            $horas = $inicio->diff($fin)->h;
            $fin_aux = clone ($inicio);
            $fin_aux = $fin_aux->add(date_interval_create_from_date_string("+{$horas} hours"));
            while ($inicio < $fin) {
                if ($fin_aux->diff($fin)->invert) {
                    $fin_aux = $fin;
                }
                $data = $this->calcularHorasPorHorario($inicio, $fin_aux, $continuo);
                $fecha = clone ($inicio);
                $data['fecha'] = $fecha->format('d-m-Y');
                $fecha = clone ($inicio);
                $data['entrada'] = $inicio->format('h:i A');
                $fecha = clone ($fin_aux);
                $data['salida'] = $fecha->format('h:i A');
                $jornadas[] = $data;

                $inicio = $inicio->add(date_interval_create_from_date_string("+1 days"));
                $fin_aux = $fin_aux->add(date_interval_create_from_date_string("+1 days"));
            }
        }
        return $jornadas;
    }

    private function calcularHorasPorHorario($inicio, $fin, $continuo)
    {
        $interval = $inicio->diff($fin);
        if ($interval->h <= 8 and $interval->d == 0) { //Horas ordinarias
            //CONDICIONES PARA CALCULAR LAS HORAS DE CADA HORARIO
            $H = $this->calcularHoras($inicio, $fin, $continuo);
            return [
                'HOD' => $H['HD'],
                'HON' => $H['HN'],
                'HODF' => $H['HDF'],
                'HONF' => $H['HNF'],
                'HEDO' => 0,
                'HENO' => 0,
                'HEDF' => 0,
                'HENF' => 0
            ];
        } else { //Horas extra
            $fin_aux = clone ($inicio);
            $HO = $this->calcularHoras($inicio, $fin_aux->add(date_interval_create_from_date_string("+8 hours")), $continuo);
            $HE = $this->calcularHoras($fin_aux, $fin, $continuo);

            return [
                'HOD' => $HO['HD'],
                'HON' => $HO['HN'],
                'HODF' => $HO['HDF'],
                'HONF' => $HO['HNF'],
                'HEDO' => $HE['HD'],
                'HENO' => $HE['HN'],
                'HEDF' => $HE['HDF'],
                'HENF' => $HE['HNF']
            ];
        }
    }

    private function calcularHoras($inicio, $fin, $continuo)
    {
        $fecha = $inicio;
        $HD = 0;
        $HN = 0;
        $HDF = 0;
        $HNF = 0;
        // print_r($continuo);
        if ((Feriados::isFeriado($fecha->format('d-m-Y')) || $inicio->format('D') == 'Sun') && $continuo) { //festiva
            while ($inicio != $fin) {
                $fin_aux = clone ($inicio);
                $fin_aux = $fin_aux->add(date_interval_create_from_date_string("+1 hours"));
                if ($fin_aux->diff($fin)->invert) {
                    $fin_aux = $fin;
                }
                $h = $inicio->diff($fin_aux)->h;
                if ($h == 0) {
                    $h = round(($inicio->diff($fin_aux)->i / 60), 1);
                }
                if ($inicio->format('H') >= 6 and $inicio->format('H') < 21) { //diurno
                    $HDF += $h;
                } else { //nocturno
                    $HNF += $h;
                }

                $inicio = $fin_aux;
            }
        } else { //no festiva
            while ($inicio != $fin) {
                $fin_aux = clone ($inicio);
                $fin_aux = $fin_aux->add(date_interval_create_from_date_string("+1 hours"));
                if ($fin_aux->diff($fin)->invert) {
                    $fin_aux = $fin;
                }

                $h = $inicio->diff($fin_aux)->h;
                if ($h == 0) {
                    $h = round(($inicio->diff($fin_aux)->i / 60), 1);
                }

                if ($inicio->format('H') >= 6 and $inicio->format('H') < 21) { //diurno
                    $HD += $h;
                } else { //nocturno
                    $HN += $h;
                }

                $inicio = $fin_aux;
            }
        }

        return ['HD' => $HD, 'HN' => $HN, 'HDF' => $HDF, 'HNF' => $HNF];
    }
}
