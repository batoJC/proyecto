<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\User;
use Illuminate\Http\Request;
use PDF;
use Auth;


class CertificadoMoraController extends Controller
{

    public function enMora()
    {
        session(['section' => 'en_mora']);
        $conjunto = Conjunto::find(session('conjunto'));
        return view('admin.listados.en_mora')
            ->with('moras', $this->generarEnMora())
            ->with('conjuntos', $conjunto);
    }


    public function pdfEnMora()
    {
        $pdf = null;
        $pdf = PDF::loadView('admin.listados.pdfMora', [
            'moras' => $this->generarEnMora()
        ]);
        return $pdf->stream();
    }

    private function generarEnMora()
    {
        $propietarios = User::where([
            ['id_rol', 3],
            ['id_conjunto', session('conjunto')]
        ])->get();

        $moras = array();
        foreach ($propietarios as $propietario) {
            $data = $propietario->cuentas();
            if (count($data) > 0) {

                $interes = 0;
                $capital = 0;

                foreach ($data as $cuenta) {
                    $interes += $cuenta['interes'];
                    $capital += $cuenta['valor'];
                }

                $moras[] = array('propietario' => $propietario, 'capital' => $capital, 'interes' => $interes);
            }
        }
        return $moras;
    }

    public function certificado(Request $request)
    {
        $request['data'] = $this->cuotas(User::find($request->id));
        $pdf = PDF::loadView(
            'admin.PDF.certificadoMora',
            $request->all()
        );
        return $pdf->stream();
    }


    public function cuotas(User $propietario)
    {
        $mes = array(
            'Jan' => 'ENERO',
            'Feb' => 'FEBRERO',
            'Mar' => 'MARZO',
            'Apr' => 'ABRIL',
            'May' => 'MAYO',
            'Jun' => 'JUNIO',
            'Jul' => 'JULIO',
            'Aug' => 'AGOSTO',
            'Sep' => 'SEPTIEMBRE',
            'Oct' => 'OCTUBRE',
            'Nov' => 'NOVIEMBRE',
            'Dec' => 'DICIEMBRE',
        );
        $cuentas = $propietario->cuentas();
        $unidades = $propietario->unidades;
        $salida = array();
        $aux = array();
        $total = 0;
        foreach ($cuentas as $cuenta) {
            if($cuenta['valor'] > 0){
                $fecha = ($cuenta['vigencia_fin']) ? $cuenta['vigencia_fin'] : $cuenta['vigencia_inicio'];
                $total += $cuenta['valor'];
                $aux[] = ['concepto' => $cuenta['concepto'], 'valor' => '$ ' . number_format($cuenta['valor']), 'fecha_vencimiento' => date('d',strtotime($fecha)).' de '.$mes[date('M', strtotime($cuenta['vigencia_inicio']))].' de '.date('Y',strtotime($fecha))];
            }
        }
        $salida['cuentas'] = $aux;
        $salida['propietario'] = $propietario->nombre_completo.'. CC. '.$propietario->numero_cedula;
        $salida['total'] = '$ ' . number_format($total);
        return $salida;
    }
}
