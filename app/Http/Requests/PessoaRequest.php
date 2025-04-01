<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pes_id' => 'required',
            'pes_nome' => 'required',
            'pes_data_nascimento' => 'required',
            'pes_mae' => '',
            'pes_pai' => '',
        ];
    }
}
