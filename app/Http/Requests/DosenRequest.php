<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DosenRequest extends FormRequest
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
        $isUpdate = $this->route('id') !== null;

        return [
            'nidn' => $isUpdate
                ? 'required|string|max:20'
                : 'required|string|max:20|unique:dosen,nidn',
            'nama_lengkap' => 'required|string|max:255',
            'gelar' => 'required|string|max:100',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'jabatan_struktural' => 'nullable|string|max:100',
            'kuota_max' => 'required|integer|min:1|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => $isUpdate
                ? 'required|email|max:255'
                : 'required|email|max:255|unique:dosen,email',
            'alamat' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.unique' => 'NIDN sudah terdaftar.',
            'nidn.max' => 'NIDN maksimal 20 karakter.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',

            'gelar.required' => 'Gelar wajib diisi.',
            'gelar.max' => 'Gelar maksimal 100 karakter.',

            'jabatan_fungsional.max' => 'Jabatan fungsional maksimal 100 karakter.',

            'jabatan_struktural.max' => 'Jabatan struktural maksimal 100 karakter.',

            'kuota_max.required' => 'Kuota maksimal wajib diisi.',
            'kuota_max.integer' => 'Kuota maksimal harus berupa angka.',
            'kuota_max.min' => 'Kuota maksimal minimal 1.',
            'kuota_max.max' => 'Kuota maksimal terlalu besar.',

            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            'alamat.required' => 'Alamat wajib diisi.',
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
