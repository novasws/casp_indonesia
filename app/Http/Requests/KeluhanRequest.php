<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeluhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'     => ['required', 'string', 'max:100'],
            'hp'       => ['required', 'string', 'regex:/^08[0-9]{8,12}$/'],
            'email'    => ['required', 'email', 'max:150'],
            'kategori' => ['required', 'string'],
            'urgensi'  => ['required', 'string'],
            'isi'      => ['required', 'string', 'min:20', 'max:5000'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::error('Validasi Keluhan Gagal: ' . json_encode($validator->errors()->all()));
        parent::failedValidation($validator);
    }

    public function messages(): array
    {
        return [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'hp.required'       => 'Nomor HP wajib diisi.',
            'hp.regex'          => 'Format nomor HP tidak valid. Contoh: 08123456789.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'kategori.required' => 'Kategori keluhan wajib dipilih.',
            'kategori.in'       => 'Kategori keluhan tidak valid.',
            'urgensi.required'  => 'Tingkat urgensi wajib dipilih.',
            'isi.required'      => 'Uraian keluhan wajib diisi.',
            'isi.min'           => 'Uraian keluhan minimal 20 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'     => 'Nama Lengkap',
            'hp'       => 'Nomor HP',
            'email'    => 'Alamat Email',
            'kategori' => 'Kategori Keluhan',
            'urgensi'  => 'Tingkat Urgensi',
            'isi'      => 'Uraian Keluhan',
        ];
    }
}