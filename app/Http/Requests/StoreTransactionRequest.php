<?php

namespace App\Http\Requests;

use App\Enums\TransactionType;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreTransactionRequest extends FormRequest
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
            'tgl_transaksi' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'tipe' => ['required', new Enum(TransactionType::class)],
            'nominal' => 'required|numeric|min:0.01',
            'kategori_id' => 'nullable|exists:categories,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors())
        );
    }
}
