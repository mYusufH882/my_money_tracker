<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetInitialBalanceRequest extends FormRequest
{
    use ApiResponseTrait;

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
            'initial_balance' => 'required|numeric|min:0|max:999999999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'initial_balance.required' => 'Saldo awal wajib diisi',
            'initial_balance.numeric' => 'Saldo awal harus berupa angka',
            'initial_balance.min' => 'Saldo awal tidak boleh negatif',
            'initial_balance.max' => 'Saldo awal terlalu besar',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors())
        );
    }
}
