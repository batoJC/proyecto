<?php

namespace App\Http\Controllers;

use PDF;
use QR;
use App\Conjunto;
use App\Consecutivos;
use App\DetalleEgreso;
use App\Egreso;
use App\Ejecucion_presupuestal_individual;
use App\Proveedor;
use Illuminate\Http\Request;
use ZIP;
use DB;


class EgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'egresos']);

        $fecha_inicio = DB::table('egresos')->where('conjunto_id', session('conjunto'))->min('fecha');
        $fecha_fin = DB::table('egresos')->where('conjunto_id', session('conjunto'))->max('fecha');

        $conjuntos = Conjunto::find(session('conjunto'));
        $egresos = Egreso::where('conjunto_id', session('conjunto'))->get();
        return view('admin.egresos.index')
            ->with('fecha_inicio', $fecha_inicio)
            ->with('fecha_fin', $fecha_fin)
            ->with('egresos', $egresos)
            ->with('conjuntos', $conjuntos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        //consultar consecutivo
        $consecutivo = Consecutivos::find($request->consecutivo);

        $egreso = new Egreso();
        $egreso->prefijo = $consecutivo->prefijo;
        $egreso->numero = $consecutivo->numero;
        $egreso->fecha = $request->fecha;
        $egreso->factura = $request->nro_factura;
        $egreso->retencion = $request->retencion;
        $egreso->soporte = '';

        //guardar imagen
        if ($request->hasFile('soporte')) {
            $file = time() . '.' . $request->soporte->getClientOriginalExtension();
            $request->soporte->move(public_path('imgs/private_imgs'), $file);
            $egreso->soporte = $file;
        }

        $egreso->proveedor_id = $request->proveedor;
        $egreso->conjunto_id = session('conjunto');
        if ($egreso->save()) {
            $consecutivo->numero++;
            $consecutivo->save();

            for ($i = 1; $i < $request->nro_detalles; $i++) {
                $data = explode('##', $request['detalle_' . $i]);
                $detalle = new DetalleEgreso();
                $detalle->fill(array(
                    'codigo' => $data[0],
                    'concepto' => $data[1],
                    'valor' => $data[2],
                    'presupuesto_id' => $data[3],
                    'egreso_id' => $egreso->id
                ));
                $detalle->save();
            }

            return array('res' => 1, 'msg' => 'Egreso guardado correctamente', 'id' => $egreso->id);
        }
        // } catch (\Throwable $th) {
        //     return array('res' => 0, 'msg' => 'Ocurrió un error un error al guardar el egreso');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function show(Egreso $egreso)
    {
        return view('admin.egresos.ver')->with('egreso', $egreso);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function edit(Egreso $egreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Egreso $egreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Egreso $egreso)
    {
        //
    }

    public function agregar()
    {
        $consecutivos = Consecutivos::where('conjunto_id', session('conjunto'))->get();
        $proveedores = Proveedor::where('conjunto_id', session('conjunto'))->get();
        $presupuestos = Ejecucion_presupuestal_individual::join('ejecucion_presupuestal_total', 'ejecucion_presupuestal_individual.id_ejecucion_pre_total', '=', 'ejecucion_presupuestal_total.id')
            ->where([
                ['ejecucion_presupuestal_individual.conjunto_id', session('conjunto')],
                ['ejecucion_presupuestal_total.tipo', 'egreso'],
                ['ejecucion_presupuestal_total.vigente', true]
            ])
            ->select('ejecucion_presupuestal_individual.*')
            ->get();


        return view('admin.egresos.agregar')
            ->with('presupuestos', $presupuestos)
            ->with('proveedores', $proveedores)
            ->with('consecutivos', $consecutivos);
    }

    public function buscar(Request $request)
    {
        $egreso = Egreso::where([
            ['prefijo', $request->prefijo],
            ['numero', $request->numero]
        ])->get();


        if (count($egreso) > 0) {
            return view('admin.egresos.ver')

                ->with('egreso', $egreso[0]);
        } else {
            return '<br><h3 class="text-center">No existe ningún registro.</h3>';
        }
    }

    public function listar(Request $request)
    {
        $egresos = Egreso::where([
            ['fecha', '<=', $request->fecha_fin],
            ['fecha', '>=', $request->fecha_inicio],
        ])->get();

        if (count($egresos) > 0) {

            $data = '';
            foreach ($egresos as $egreso) {
                $data .= $egreso->id . ';';
            }
            $data = trim($data, ';');

            return view('admin.egresos.listar')->with('egresos', $egresos)->with('data', $data);
        } else {
            return '<br><h3 class="text-center">No existen registros en esas fechas.</h3>';
        }
    }

    public function pdf(Egreso $egreso)
    {
        $pdf = null;
        $files = glob(public_path('qrcodes') . '/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        $text_qr = $egreso->conjunto->nombre . "\n\r";
        $text_qr .= $egreso->prefijo . ' ' . $egreso->numero . "\n\r";
        $text_qr .= $egreso->proveedor->nombre_completo . "\n\r";
        $text_qr .= '$ ' . number_format($egreso->valorTotal());


        QR::format('png')->size(140)->margin(2)->generate($text_qr, public_path('qrcodes/qrcodeegreso_' . $egreso->id . '.png'));

        $pdf = PDF::loadView('admin.egresos.pdf', [
            'egreso' => $egreso
        ]);
        return $pdf->stream();
    }


    public function anular(Request $request, Egreso $egreso)
    {
        $egreso->anulado = true;
        $egreso->detalle = $request->detalle;
        if ($egreso->save()) {
            return array('res' => 1, 'msg' => 'Egreso anulado correctamente');
        } else {
            return array('res' => 0, 'msg' => 'No se logró anular el egreso');
        }
    }

    public function downloadZip(Request $request)
    {

        //crear carpeta
        $nombre_carpeta = time();
        mkdir(public_path() . '/' . $nombre_carpeta);


        //colocar todos los pdfs de los egresos en ella
        $egresos = explode(';', $request->data);

        foreach ($egresos as $egreso) {
            $egreso = Egreso::find($egreso);
            $archivo = $this->pdf($egreso);
            file_put_contents(public_path('/' . $nombre_carpeta . '/') . $egreso->prefijo . ' ' . $egreso->numero . ".pdf", $archivo);
        }

        //generar el ZIP y descargamos
        $files = public_path() . '\\' . $nombre_carpeta;
        $zip = new ZIP();

        /* Le indicamos en que carpeta queremos que se genere el zip y los comprimimos*/
        $zip->make(public_path('temp/' . $nombre_carpeta . '.zip'))->add($files)->close();

        //eliminar caropeta
        $this->eliminarCarpeta($nombre_carpeta);

        /* Por último, si queremos descarlos, indicaremos la ruta del archiv, su nombre
        y lo descargaremos*/
        return response()->download(public_path('temp/' . $nombre_carpeta . '.zip'));
    }


    private function eliminarCarpeta($nombre)
    {
        foreach (glob(public_path($nombre) . '\\*') as $archivo) {
            if (is_dir($archivo)) {
                $data = explode($nombre, $archivo)[1];
                $this->eliminarCarpeta($nombre . $data);
            } else {
                @unlink($archivo);
            }
        }
        rmdir($nombre);
    }
}
