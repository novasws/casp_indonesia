<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnboardingStep1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'   => ['required', 'string', 'max:100'],
            'hp'     => ['required', 'string', 'regex:/^08[0-9]{8,12}$/'],
            'email'  => ['required', 'email', 'max:150'],
            'bidang' => ['required', 'string', 'in:Hukum Perdata,Hukum Keluarga,Hukum Bisnis,Hukum Properti,Hukum Ketenagakerjaan,Hukum Pidana,Lainnya'],
            'keluhan'=> ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'hp.required'     => 'Nomor HP wajib diisi.',
            'hp.regex'        => 'Format nomor HP tidak valid. Contoh: 08123456789.',
            'email.required'  => 'Alamat email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'bidang.required' => 'Bidang hukum wajib dipilih.',
            'bidang.in'       => 'Bidang hukum tidak valid.',
            'keluhan.required'=> 'Deskripsi keluhan wajib diisi.',
        ];
    }
}