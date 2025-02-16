<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CepRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileCompleteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cpf_cnpj' => ['required', 'min:11', 'max:14'],
            'postalCode' => ['required', new CepRule()],
            'addressNumber' => ['required',],
            'addressComplement' => ['required', ],
        ];
    }
}
