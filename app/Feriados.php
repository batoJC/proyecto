<?php

namespace App;


class Feriados
{
    //

    public static function isFeriado($fecha)
    {
        $holidays = Feriados::generateHolidays(date('Y', strtotime($fecha)));
        // usort($holidays, function ($a, $b) {
        //     return strtotime($a) - strtotime($b);
        // });
        return in_array($fecha, $holidays);
    }

    // private function ordenar($a, $b)
    // {
    //     return strtotime($a) - strtotime($b);
    // }


    private static function generateHolidays($year)
    {
        // Feriados::easterMonth--;
        $a = floor($year % 19);
        $b = floor($year % 4);
        $c = floor($year % 7);
        $k = floor($year / 100);
        $p = floor((13 + 8 * $k) / 25);
        $q = floor($k / 4);
        $M = floor((15 - $p + $k - $q) % 30);
        $N = floor((4 + $k - $q) % 7);
        $d = floor((19 * $a + $M) % 30);
        $e = floor((2 * $b + 4 * $c + 6 * $d + $N) % 7);

        $pascua = [];
        if (($d + $e) < 10) {
            $pascua = ['month' => 3, 'day' => ($d + $e + 22)];
        } else {
            if (($d + $e - 9) != 26) {
                if (($d + $e - 9) == 25 and $a > 10) {
                    $pascua = ['month' => 4, 'day' => 18];
                } else {
                    $pascua = ['month' => 4, 'day' => ($d + $e - 9)];
                }
            } else {
                $pascua = ['month' => 4, 'day' => 19];
            }
        }

        $holidays = array();

        //fijos
        $holidays[] = "01-01-{$year}"; // Primero de Enero
        $holidays[] = "01-05-{$year}"; // Dia del trabajo 1 de mayo
        $holidays[] = "20-07-{$year}"; //Independencia 20 de Julio
        $holidays[] = "07-08-{$year}"; //Batalla de boyaca 7 de agosto
        $holidays[] = "08-12-{$year}"; //Maria inmaculada 8 de diciembre
        $holidays[] = "25-12-{$year}"; //Navidad 25 de diciembre

        //según la pascua
        $fechaPascua = "{$year}-{$pascua['month']}-{$pascua['day']}";

        //jueves santo
        $date = date("d-m-Y", strtotime($fechaPascua . "- 3 days"));
        $holidays[] = $date;

        //viernes santo
        $date = date("d-m-Y", strtotime($fechaPascua . "- 2 days"));
        $holidays[] = $date;

        //Ascención de Jesús
        $date = date("d-m-Y", strtotime($fechaPascua . "+ 43 days"));
        $holidays[] = $date;

        //Corpus Christi
        $date = date("d-m-Y", strtotime($fechaPascua . "+ 64 days"));
        $holidays[] = $date;

        //Sagrado corazón de Jesús 
        $date = date("d-m-Y", strtotime($fechaPascua . "+ 71 days"));
        $holidays[] = $date;

        //Fechas trasladables
        //Epífania
        $date = Feriados::calculate("06-01-{$year}");
        $holidays[] = $date;
        //Día de san jóse
        $date = Feriados::calculate("19-03-{$year}");
        $holidays[] = $date;

        //Día de san pedro y san pablo
        $date = Feriados::calculate("29-06-{$year}");
        // echo $date;
        $holidays[] = $date;

        //Asunción de la virgen
        $date = Feriados::calculate("15-08-{$year}");
        $holidays[] = $date;

        //Día de la raza
        $date = Feriados::calculate("12-10-{$year}");
        $holidays[] = $date;

        //Día de todos los santos
        $date = Feriados::calculate("01-11-{$year}");
        $holidays[] = $date;

        //Día de independencia de cartagena
        $date = Feriados::calculate("11-11-{$year}");
        $holidays[] = $date;

        return $holidays;
    }

    private static function calculate($fecha)
    {

        $dayOfWeek = date('N', strtotime($fecha));
        if ($dayOfWeek != 1) {
            $sumar = 8 - $dayOfWeek;
            return date('d-m-Y', strtotime($fecha . "+ {$sumar} days"));
        }
        return $fecha;
    }
}
