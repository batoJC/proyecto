<?php

namespace App\Http\Controllers;

use App\Conjunto;
use PDF;
use App\User;
use Illuminate\Http\Request;

class PazSalvoController extends Controller
{
    public function pazSalvo()
    {
        session(['section' => 'paz_salvo']);
        $conjunto = Conjunto::find(session('conjunto'));
        return view('admin.listados.paz_salvo')
            ->with('propietarios', $this->generarPazSalvo())
            ->with('conjuntos', $conjunto);
    }


    public function pdfPazSalvo()
    {
        $pdf = null;
        $pdf = PDF::loadView('admin.listados.pdfPazSalvo', [
            'propietarios' => $this->generarPazSalvo()
        ]);
        return $pdf->stream();
    }




    private function generarPazSalvo()
    {
        $propietarios = User::where([
            ['id_rol', 3],
            ['id_conjunto', session('conjunto')]
        ])->get();

        $salida = array();
        foreach ($propietarios as $propietario) {
            $data = $propietario->cuentas();
            if (count($data) == 0) {
                $salida[] = $propietario;
            }
        }
        return $salida;
    }

    public function cuerpoCarta(User $propietario)
    {
        $cuerpo = $propietario->nombre_completo;
        $unidades_nombre = '';
        foreach ($propietario->unidades as $unidad) {
           $unidades_nombre .= $unidad->tipo->nombre.' '.$unidad->numero_letra.',';
        }
        $unidades_nombre = trim($unidades_nombre,',');
        $cuerpo = 'Que '.mb_strtoupper($propietario->nombre_completo,'UTF-8').' con la cédula de ciudadanía No. '.$propietario->numero_cedula.' propietario(a) de '.$unidades_nombre.' se encuentra a PAZ Y SALVO al (FECHA FIN VIGENCIA - CAMBIAR), por cuotas de Administración póliza de seguro de áreas comunes, cuotas extraordinarias, multas e intereses por mora.';
        return ['cuerpo' => $cuerpo];
    }

    public function pazSalvoPdf(Request $request)
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

        $fecha =  date('d', strtotime(date('D'))) . ' de ' . $mes[date('M', strtotime(date('M')))] . ' de ' . date('Y', strtotime(date('Y')));


        $request['fecha'] = $fecha;

        $pdf = null;
        $pdf = PDF::loadView('admin.PDF.paz_salvo', $request->all());
        return $pdf->stream();
    }
}
