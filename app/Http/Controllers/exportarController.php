<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Unidad;
use Illuminate\Http\Request;
use ZIP;
use PDF;


class exportarController extends Controller
{
    //
    public function index()
    {
        session(['section' => 'evidencias']);
        $conjuntos = Conjunto::find(session('conjunto'));
        return view('admin.exportar.index')->with('conjuntos', $conjuntos);
    }


    public function downloadSeveral(Request $request)
    {
        try {
            //validar que si existan datos
            $cuantos = count($request->all());
            if ($cuantos > 0) {
                $conjunto = Conjunto::find(session('conjunto'));
                @unlink(public_path("exports/{$conjunto->id}.zip"));
                $this->main();
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
        } catch (\Throwable $th) {
            return $th;
        }
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

    private function unidades()
    {
        $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/unidades";
        mkdir(public_path($carpeta));
        foreach ($unidades as $unidad) {
            $pdf = PDF::loadView('admin.exportar.unidad', [
                'unidad' => $unidad
            ]);
            $archivo = $pdf->stream();
            $nombre_archivo = "exports/{$conjunto->id}/unidades/{$unidad->tipo->nombre} {$unidad->numero_letra}.pdf";
            file_put_contents(public_path($nombre_archivo), $archivo);
        }
    }

    //TODO
    private function pqr()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/pqr";
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
    private function novedades_conjunto()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/novedades_conjunto";
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
    private function evidencias()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/evidencias";
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
    private function novedades_unidades()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/novedades_unidades";
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
    private function ingresos_y_retiros()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/ingresos_y_retiros";
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
    private function empleados()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/empleados";
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
    private function mantenimientos()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/mantenimientos";
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
    private function zonas_sociales()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/zonas_sociales";
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
    private function reservas()
    {
        // $pdf = null;
        $conjunto = Conjunto::find(session('conjunto'));
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->get();
        $carpeta = "exports/{$conjunto->id}/reservas";
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
}
