<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\EmpleadosConjunto;
use App\Feriados;
use App\Jornada;
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

    public function informacion(){
        $datos = array();
        $datos['salario'] = 993800;
        $datos['horas_jornada'] = 240;
        $datos['jornada_ordinaria'] = 8;
        $datos['inicio_jornada'] = '6:00';
        $datos['final_jornada'] = '21:00';
        $datos['recargo_ordinario_nocturno'] = 35;
        $datos['recargo_ordinario_diurno_festivo'] = 75;
        $datos['recargo_ordinario_nocturno_festivo'] = 75;
        $datos['hora_extra_ordinaria_diurna'] = 1.25;
        $datos['hora_extra_ordinaria_nocturna'] = 1.75;
        $datos['hora_extra_ordinaria_diurna_fesiva'] = 2.00;
        $datos['hora_extra_ordinaria_nocturna_festiva'] = 2.50;


        return view('admin.liquidador.informacion')->with('datos',$datos);
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
                if ($fin_aux->diff($fin)->invert) {
                    $fin_aux = $fin;
                }
                $data = $this->calcularHorasPorHorario($inicio, $fin_aux);
                $fecha = clone ($inicio);
                $data['fecha'] = $fecha->format('d-m-Y');
                $data['entrada'] = $inicio->format('h:i A');
                $fecha = clone($fin_aux);
                $data['salida'] = $fecha->format('h:i A');
                // $jornada = new Jornada();
                // $jornada->fillable($data);
                $jornadas[] = $data;

                $inicio = $fin_aux;
                $hora_inicio = 0;
            }
        } else { //Todos los días un mismo horario
            $horas = $inicio->diff($fin)->h;
            // echo $horas;
            $fin_aux = clone ($inicio);
            $fin_aux = $fin_aux->add(date_interval_create_from_date_string("+{$horas} hours"));
            while ($inicio < $fin) {
                if ($fin_aux->diff($fin)->invert) {
                    $fin_aux = $fin;
                }
                $data = $this->calcularHorasPorHorario($inicio, $fin_aux);
                $fecha = clone ($inicio);
                $data['fecha'] = $fecha->format('d-m-Y');
                $fecha = clone ($inicio);
                $data['entrada'] = $inicio->format('h:i A');
                $fecha = clone($fin_aux);
                $data['salida'] = $fecha->format('h:i A');
                $jornadas[] = $data;

                $inicio = $inicio->add(date_interval_create_from_date_string("+1 days"));
                $fin_aux = $fin_aux->add(date_interval_create_from_date_string("+1 days"));
            }
        }
        return $jornadas;
    }

    private function calcularHorasPorHorario($inicio, $fin)
    {
        $interval = $inicio->diff($fin);
        if ($interval->h <= 8 and $interval->d == 0) { //Horas ordinarias
            //CONDICIONES PARA CALCULAR LAS HORAS DE CADA HORARIO
            $H = $this->calcularHoras($inicio, $fin);
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
            $HO = $this->calcularHoras($inicio, $fin_aux->add(date_interval_create_from_date_string("+8 hours")));
            $HE = $this->calcularHoras($fin_aux, $fin);

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

    private function calcularHoras($inicio, $fin)
    {
        $fecha = $inicio;
        $HD = 0;
        $HN = 0;
        $HDF = 0;
        $HNF = 0;
        if (Feriados::isFeriado($fecha->format('d-m-Y')) || $inicio->format('D') == 'Sun') { //festiva
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
                // echo "aquí 2";
            }
        }

        return ['HD' => $HD, 'HN' => $HN, 'HDF' => $HDF, 'HNF' => $HNF];
    }
}
