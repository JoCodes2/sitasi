<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('id') !== null;

        return [
            'nama'  => 'required|string|max:255',

            'email' => $isUpdate
                ? 'required|email|max:255'
                : 'required|email|max:255|unique:users,email',

            // 🔥 PASSWORD (konsisten dengan DosenRequest)
            'password' => $isUpdate
                ? 'nullable|string|min:8'
                : 'required|string|min:8',

            'role' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max'      => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah terdaftar.',

            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',

            'role.required' => 'Role wajib dipilih.',
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
