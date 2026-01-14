<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class KepakaranRequest extends FormRequest
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
            'dosen_id' => 'required|uuid|exists:dosen,id',
            'topik_id' => 'required|uuid|exists:topik_penelitian,id',
            'persentase' => 'required|integer|min:0|max:100',
        ];
    }


    public function messages(): array
    {
        return [
            'dosen_id.required' => 'Dosen wajib dipilih.',
            'dosen_id.uuid' => 'Format ID dosen tidak valid.',
            'dosen_id.exists' => 'Dosen tidak ditemukan.',

            'topik_id.required' => 'Topik penelitian wajib dipilih.',
            'topik_id.uuid' => 'Format ID topik penelitian tidak valid.',
            'topik_id.exists' => 'Topik penelitian tidak ditemukan.',

            'persentase.required' => 'Persentase wajib diisi.',
            'persentase.integer' => 'Persentase harus berupa angka.',
            'persentase.min' => 'Persentase minimal 0.',
            'persentase.max' => 'Persentase maksimal 100.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'code'    => 422,
                'status'  => 'validation_failed',
                'message' => 'Check your input data',
                'data'    => $validator->errors(),
            ], 422)
        );
    }
}
