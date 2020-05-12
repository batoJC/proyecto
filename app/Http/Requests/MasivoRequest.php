<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'archivo'       => 'required|mimes:xlsx',
            'codigo'        => 'required|numeric',
            'hidden_codigo' => 'required|numeric|same:codigo',
        ];
    }
}
