<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'billingType' => 'required',
            'value' => 'required',
            'creditCardNumber' => ['required_if:billingType,credit_card','integer', 'nullable'],
            'holderName' => ['required_if:billingType,credit_card', 'nullable'],
            'expiryMonth' => ['required_if:billingType,credit_card', 'integer', 'between:1,12', 'nullable'],
            'expiryYear' => ['required_if:billingType,credit_card','integer', 'digits:4', 'nullable'],
            'ccv' => ['required_if:billingType,credit_card', 'integer', 'nullable'],
        ];
    }

}
