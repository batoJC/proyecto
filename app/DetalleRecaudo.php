<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleRecaudo extends Model
{
    //

    public function concepto()
    {
        $concepto = '';

        switch ($this->tipo_de_cuota) {
            case 'Interes Saldo Inicial':
                $concepto = 'INTERES ';
                break;
            case 'Interes Cuota Administrativa':
                $concepto = 'INTERES ';
                break;
            case 'Interes Cuota Extraordinaria':
                $concepto = 'INTERES ';
                break;
            case 'Interes Otro Cobro':
                $concepto = 'INTERES ';
                break;
            case 'Interes Multa':
                $concepto = 'INTERES ';
                break;
        }

        return $concepto . $this->concepto;
    }
}
