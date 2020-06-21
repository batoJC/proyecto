<?php

namespace App\Http\Controllers;

use App\Carta;
use App\Conjunto;
use App\EmpleadosConjunto;
use App\Evidencia;
use App\FlujoEfectivo;
use App\Mantenimiento;
use App\NovedadesConjunto;
use App\Reserva;
use App\Unidad;
use App\User;
use App\Zona_Comun;
use Illuminate\Http\Request;
use ZIP;
use PDF;
use QR;
use setasign\Fpdi\Fpdi;

class exportarController extends Controller
{
    //
    public function index()
    {
        session(['section' => 'evidencias']);
        $conjuntos = Conjunto::find(session('conjunto'));
        return view('admin.exportar.index')->with('conjuntos', $conjuntos);
    }


    public function probarPDF()
    {
        $pdf = null;
        // unidad
        // $unidad = Unidad::find(4);
        // $pdf = PDF::loadView('admin.exportar.unidad_listados', [
        //     'unidad' => $unidad
        // ])->setPaper('A4', 'landscape');

        //pqr
        // $propietario = User::find(10);
        // $pdf = PDF::loadView('admin.exportar.pqrs', [
        //     'propietario' => $propietario
        // ]);


        //evidencias
        // $evidencias = Evidencia::where([
        //     ['conjunto_id', 1],
        // ])->get();
        // $pdf = PDF::loadView('admin.exportar.evidencias', [
        //     'evidencias' => $evidencias
        // ]);


        //empleados conjunto
        $conjunto = Conjunto::find(session('conjunto'));
        $empleados = EmpleadosConjunto::where('conjunto_id', session('conjunto'))->get();
        $pdf = PDF::loadView('admin.exportar.empleados_conjunto', [
            'empleados' => $empleados
        ]);

        return $pdf->stream();
    }

    public function downloadSeveral(Request $request)
    {
        // try {
        //validar que si existan datos
        $cuantos = count($request->all());
        if ($cuantos > 0) {
            $conjunto = Conjunto::find(session('conjunto'));
            @unlink(public_path("exports/{$conjunto->id}.zip"));
            @$this->main();
            $nombre_carpeta = '';
            if ($cuantos == 2) {
                $generar = '';
                foreach ($request->all() as $key => $value) {
                    $generar = $key;
                    break;
                }
                $this->generar($generar);
                $nombre_carpeta = "exports/{$conjunto->id}/{$generar}";
            } else {
                foreach ($request->all() as $generar => $value) {
                    // echo $generar;
                    $this->generar($generar);
                }
                $nombre_carpeta = "exports/{$conjunto->id}";
            }
            //generar el ZIP y descargamos
            $files = public_path($nombre_carpeta);
            $zip = new ZIP();
            $zip->make(public_path('exports/' . $conjunto->id . '.zip'))->add($files)->close();
            // $this->main();
            return ['data' => 'exports/' . $conjunto->id . '.zip'];
        } else {
            return ['res' => 0, 'msg' => 'Debe de seleccionar al menos un item.'];
        }
        // } catch (\Throwable $th) {
        //     return $th;
        // }
    }

    //funciones privadas para generar las carpetas y sus contenidos
    private function main()
    {
        try {
            $conjunto = Conjunto::find(session('conjunto'));
            $carpeta = public_path('exports/' . $conjunto->id);
            if (!file_exists($carpeta)) {
                mkdir($carpeta);
            } else {
                $this->eliminarCarpeta($carpeta);
                mkdir($carpeta);
            }
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al crear la carpeta para iniciar la exportación'];
        }
    }

    private function generar($info)
    {
        //TOOD: colocar todos los listados
        switch ($info) {
            case 'unidades':
                $this->unidades();
                break;
            case 'pqr':
                $this->pqr();
                break;
            case 'novedades_conjunto':
                $this->novedades_conjunto();
                break;
            case 'novedades_unidades':
                $this->novedades_unidades();
                break;
            case 'evidencias':
                $this->evidencias();
                break;
            case 'ingresos_y_retiros':
                $this->ingresos_y_retiros();
                break;
            case 'empleados':
                $this->empleados();
                break;
            case 'mantenimientos':
                $this->mantenimientos();
                break;
            case 'zonas_sociales':
                $this->zonas_sociales();
                break;
            case 'reservas':
                $this->reservas();
                break;
            case 'inventario':
                $this->inventario();
                break;
            case 'deudas':
                $this->deudas();
                break;
            case 'flujo_efectivo':
                $this->flujo_efectivo();
                break;
        }
    }

    private function eliminarCarpeta($nombre)
    {
        foreach (glob($nombre . '/*') as $archivo) {
            if (is_dir($archivo)) {
                $data = explode($nombre, $archivo)[1];
                $this->eliminarCarpeta($nombre . $data);
            } else {
                @unlink($archivo);
            }
        }
        rmdir($nombre);
    }

    private function numPagesPDF($file)
    {
        $fp = @fopen(preg_replace("/\[(.*?)\]/i", "", $file), "r");
        $max = 0;
        while (!feof($fp)) {
            $line = fgets($fp, 255);
            if (preg_match('/\/Count [0-9]+/', $line, $matches)) {
                preg_match('/[0-9]+/', $matches[0], $matches2);
                if ($max < $matches2[0]) $max = $matches2[0];
            }
        }
        fclose($fp);
        return $max;
    }

    private function unidades()
    {
        $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/unidades";
        mkdir(public_path($carpeta));
        foreach ($unidades as $unidad) {
            //crear encabezado
            $pdf = PDF::loadView('admin.exportar.unidad_info', [
                'unidad' => $unidad
            ]);
            $archivo = $pdf->stream();
            $nombre_archivo1 = "exports/{$conjunto->id}/unidades/encabezado.pdf";
            file_put_contents(public_path($nombre_archivo1), $archivo);
            //crear cuerpo
            $pdf = PDF::loadView('admin.exportar.unidad_listados', [
                'unidad' => $unidad
            ])->setPaper('A4', 'landscape');
            $archivo = $pdf->stream();
            $nombre_archivo2 = "exports/{$conjunto->id}/unidades/cuerpo.pdf";
            file_put_contents(public_path($nombre_archivo2), $archivo);

            $nombre_archivo = "exports\\{$conjunto->id}\\unidades\\{$unidad->tipo->nombre}_{$unidad->numero_letra}.pdf";

            $pdf = new Fpdi('P', 'mm', array(210, 297));
            $pdf->addPage();
            $pdf->setSourceFile(public_path($nombre_archivo1));
            $pageid = $pdf->importPage(1);
            $pdf->useTemplate($pageid, 0, 0);
            $pdf->setSourceFile(public_path($nombre_archivo2));
            $pages = $this->numPagesPDF(public_path($nombre_archivo2));
            for ($i = 1; $i <= $pages; $i++) {
                $pdf->addPage('L');
                $pageid = $pdf->importPage($i);
                $pdf->useTemplate($pageid, 0, 0);
            }
            $pdf->SetDisplayMode('fullpage', 'single');
            $pdf->Output('F', public_path($nombre_archivo));

            @unlink(public_path($nombre_archivo1));
            @unlink(public_path($nombre_archivo2));
        }
    }

    //TODO
    private function pqr()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $propietarios = User::where([
            ['id_conjunto', session('conjunto')],
            ['id_rol', 3]
        ])->get();
        $carpeta = "exports/{$conjunto->id}/pqr";
        mkdir(public_path($carpeta));
        foreach ($propietarios as $propietario) {
            if ($propietario->pqr->count() > 0) {
                $pdf = PDF::loadView('admin.exportar.pqrs', [
                    'propietario' => $propietario
                ]);
                $archivo = $pdf->stream();
                $nombre_archivo = "exports/{$conjunto->id}/pqr/{$propietario->nombre_completo}.pdf";
                file_put_contents(public_path($nombre_archivo), $archivo);
            }
        }
    }

    //TODO
    private function novedades_conjunto()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $novedades = NovedadesConjunto::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/novedades_conjunto";
        mkdir(public_path($carpeta));
        if ($novedades->count() > 0) {
            $pdf = PDF::loadView('admin.exportar.novedades_conjunto', [
                'novedades' => $novedades
            ]);
            $archivo = $pdf->stream();
            $nombre_archivo = "exports/{$conjunto->id}/novedades_conjunto/info.pdf";
            file_put_contents(public_path($nombre_archivo), $archivo);
        }
    }


    //TODO
    private function evidencias()
    {
        $conjunto = Conjunto::find(session('conjunto'));
        $carpeta = "exports/{$conjunto->id}/evidencias";
        mkdir(public_path($carpeta));
        $evidencias = Evidencia::where([
            ['conjunto_id', session('conjunto')],
        ])->get();
        if ($evidencias->count() > 0) {
            $pdf = PDF::loadView('admin.exportar.evidencias', [
                'evidencias' => $evidencias
            ]);
            $archivo = $pdf->stream();
            $nombre_archivo = "exports/{$conjunto->id}/evidencias/info.pdf";
            file_put_contents(public_path($nombre_archivo), $archivo);
        }
    }

    //TODO
    private function novedades_unidades()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/novedades_unidades";
        mkdir(public_path($carpeta));
        foreach ($unidades as $unidad) {
            if ($unidad->novedades->count() > 0) {
                $pdf = PDF::loadView('admin.exportar.novedades_unidad', [
                    'unidad' => $unidad
                ]);
                $archivo = $pdf->stream();
                $nombre_archivo = "exports/{$conjunto->id}/novedades_unidades/{$unidad->tipo->nombre} {$unidad->numero_letra}.pdf";
                file_put_contents(public_path($nombre_archivo), $archivo);
            }
        }
    }

    //TODO
    private function ingresos_y_retiros()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $cartas = Carta::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/ingresos_y_retiros";
        mkdir(public_path($carpeta));
        foreach ($cartas as $carta) {
            $files = glob(public_path('qrcodes') . '/*');
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file); //elimino el fichero
            }

            $pdf = null;

            $text_qr = "Fecha: " . date('d-m-Y', strtotime($carta->fecha));
            $text_qr .= "\n\r Unidad: " . $carta->unidad->tipo->nombre . ' ' . $carta->unidad->numero_letra;
            $text_qr .= "\n\r Copropiedad: " . $conjunto->nombre;

            $administrador = $conjunto->administrador();

            QR::format('png')->size(180)->margin(10)->generate($text_qr, public_path('qrcodes/qrcode_' . $carta->id . '.png'));
            $pdf = PDF::loadView('admin.PDF.carta', ['carta' => $carta, 'administrador' => $administrador]);
            $archivo =  $pdf->stream();
            $nombre_archivo = "exports/{$conjunto->id}/ingresos_y_retiros/carta_{$carta->id}.pdf";
            file_put_contents(public_path($nombre_archivo), $archivo);
        }
    }

    //TODO
    private function empleados()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $empleados = EmpleadosConjunto::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/empleados";
        mkdir(public_path($carpeta));
        $pdf = PDF::loadView('admin.exportar.empleados_conjunto', [
            'empleados' => $empleados
        ]);
        $archivo = $pdf->stream();
        $nombre_archivo = "exports/{$conjunto->id}/empleados/info.pdf";
        file_put_contents(public_path($nombre_archivo), $archivo);
    }

    //TODO
    private function mantenimientos()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $mantenimientos = Mantenimiento::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/mantenimientos";
        @mkdir(public_path($carpeta));
        foreach ($mantenimientos as $mantenimiento) {
            $fecha = date('d_m_Y', strtotime($mantenimiento->fecha));
            $nombre_archivo = "exports/{$conjunto->id}/mantenimientos/mantenimiento_{$mantenimiento->id}_fecha_{$fecha}.pdf";
            // echo $nombre_archivo;
            copy(public_path("document/{$mantenimiento->archivo}"), $nombre_archivo);
        }
    }

    //TODO
    private function zonas_sociales()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $zonas_sociales = Zona_Comun::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/zonas_sociales";
        @mkdir(public_path($carpeta));
        $pdf = PDF::loadView('admin.exportar.zonas_sociales', [
            'zonas_sociales' => $zonas_sociales
        ]);
        $archivo = $pdf->stream();
        $nombre_archivo = "exports/{$conjunto->id}/zonas_sociales/info.pdf";
        file_put_contents(public_path($nombre_archivo), $archivo);
    }

    //TODO
    private function reservas()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $carpeta = "exports/{$conjunto->id}/reservas";
        mkdir(public_path($carpeta));
        $reservas_aceptadas = Reserva::join('zonas_comunes', 'zonas_comunes.id', 'reservas.zona_comun_id')
            ->where([
                ['zonas_comunes.conjunto_id', session('conjunto')],
                ['reservas.estado', 'aceptada']
            ])
            ->orderBy('reservas.fecha_inicio', 'DESC')
            ->select('reservas.*')
            ->get();
        $pdf = PDF::loadView('admin.exportar.reservas_aprovadas', [
            'reservas' => $reservas_aceptadas
        ]);
        $archivo = $pdf->stream();
        $nombre_archivo = "exports/{$conjunto->id}/reservas/reservas_aceptadas.pdf";
        file_put_contents(public_path($nombre_archivo), $archivo);

        $reservas_pendientes = Reserva::join('zonas_comunes', 'zonas_comunes.id', 'reservas.zona_comun_id')
            ->where([
                ['zonas_comunes.conjunto_id', session('conjunto')],
                ['reservas.estado', 'pendiente']
            ])
            ->orderBy('reservas.fecha_inicio', 'DESC')
            ->select('reservas.*')
            ->get();
        $pdf = PDF::loadView('admin.exportar.reservas_pendientes', [
            'reservas' => $reservas_pendientes
        ]);
        $archivo = $pdf->stream();
        $nombre_archivo = "exports/{$conjunto->id}/reservas/reservas_pendientes.pdf";
        file_put_contents(public_path($nombre_archivo), $archivo);
    }

    //TODO
    private function inventario()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/inventario";
        mkdir(public_path($carpeta));
        // foreach ($unidades as $unidad) {
        //     $pdf = PDF::loadView('admin.exportar.unidad', [
        //         'unidad' => $unidad
        //     ]);
        //     $archivo = $pdf->stream();
        //     $nombre_archivo = "exports/{$conjunto->id}/unidades/{$unidad->tipo->nombre} {$unidad->numero_letra}.pdf";
        //     file_put_contents(public_path($nombre_archivo), $archivo);
        // }

    }

    //TODO
    private function deudas()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/inventario";
        mkdir(public_path($carpeta));
        // foreach ($unidades as $unidad) {
        //     $pdf = PDF::loadView('admin.exportar.unidad', [
        //         'unidad' => $unidad
        //     ]);
        //     $archivo = $pdf->stream();
        //     $nombre_archivo = "exports/{$conjunto->id}/unidades/{$unidad->tipo->nombre} {$unidad->numero_letra}.pdf";
        //     file_put_contents(public_path($nombre_archivo), $archivo);
        // }

    }

    //TODO
    private function flujo_efectivo()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $flujo_efectivo = FlujoEfectivo::where('conjunto_id', session('conjunto'))
            ->orderBy('fecha', 'ASC')
            ->get();
        $carpeta = "exports/{$conjunto->id}/flujo_efectivo";
        mkdir(public_path($carpeta));
        $pdf = PDF::loadView('admin.exportar.flujo_efectivo', [
            'flujo_efectivo' => $flujo_efectivo
        ]);
        $archivo = $pdf->stream();
        $nombre_archivo = "exports/{$conjunto->id}/flujo_efectivo/info.pdf";
        file_put_contents(public_path($nombre_archivo), $archivo);
    }
}
