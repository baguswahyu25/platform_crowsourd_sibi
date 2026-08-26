<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/', // Minimal 1 huruf besar (kapital)
                'regex:/[0-9]/', // Minimal 1 angka
                'regex:/[!@#$%^&*(),.?":{}|<>_]/', // Minimal 1 simbol unik
                'confirmed'
            ],
            'cf-turnstile-response' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.regex' => 'Kata sandi harus berupa kombinasi: minimal 8 karakter, mengandung minimal 1 huruf besar (A-Z), 1 angka (0-9), dan 1 simbol unik (!@#$%^&* dll).',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi yang dimasukkan.',
            'cf-turnstile-response.required' => 'Verifikasi keamanan gagal. Silakan lakukan verifikasi ulang.',
        ];
    }
}
