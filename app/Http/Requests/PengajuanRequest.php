<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Validasi Tabel Pengajuan (Parent)
            'gelombang_id'    => 'required|exists:gelombang,id',
            'harapan_judul'   => 'required|in:1,2,3',
            'alasan_prioritas' => 'required',

            // Validasi Tabel Detail Pengajuan (Array)
            'details'                  => 'required|array|min:3|max:3', // Wajib mengajukan 3 judul
            'details.*.pilihan_judul'  => 'required|in:1,2,3',
            'details.*.judul'          => 'required|string|min:10|max:255',
            'details.*.latar_belakang' => 'required|string|min:50',
            'details.*.topik_id'       => 'required|exists:topik_penelitian,id',
        ];
    }

    public function messages(): array
    {
        return [
            'gelombang_id.required'    => 'Periode gelombang harus dipilih.',
            'harapan_judul.required'   => 'Pilihan prioritas judul harus ditentukan.',
            'alasan_prioritas.required' => 'Alasan prioritas judul wajib diisi.',

            // Pesan untuk Array Detail
            'details.min'              => 'Anda harus menginputkan minimal 3 pilihan judul.',
            'details.*.judul.required' => 'Judul ke-:position wajib diisi.',
            'details.*.judul.min'      => 'Judul ke-:position terlalu pendek (min. 10 karakter).',
            'details.*.latar_belakang.required' => 'Latar belakang judul ke-:position wajib diisi.',
            'details.*.latar_belakang.min'      => 'Latar belakang judul ke-:position minimal 50 karakter.',
            'details.*.topik_id.required'       => 'Topik penelitian untuk judul ke-:position harus dipilih.',
            'details.*.topik_id.exists'         => 'Topik penelitian tidak valid.',
        ];
    }

    /**
     * Custom Attribute Names (Agar pesan error lebih rapi)
     */
    public function attributes(): array
    {
        return [
            'details.*.judul' => 'Judul',
            'details.*.latar_belakang' => 'Latar Belakang',
            'details.*.topik_id' => 'Topik Penelitian',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'code'    => 422,
                'status'  => 'validation_failed',
                'message' => 'Mohon periksa kembali inputan Anda.',
                'data'    => $validator->errors(),
            ], 422)
        );
    }
}
