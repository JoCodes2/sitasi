<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule; // Import ini penting

class MahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mahasiswaId = $this->route('id');

        return [
            'nama' => 'required',


            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($mahasiswaId),
            ],


            'password' => $mahasiswaId ? 'nullable|min:8' : 'required|min:8',
            'password' => $mahasiswaId
                ? 'nullable|min:8|confirmed'
                : 'required|min:8|confirmed',

            'nim' => [
                'required',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswaId),
            ],

            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'prodi' => 'required',
            'angkatan' => 'required',
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
