<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function authorize()
    {
        return true; // pastikan user sudah login
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],

            // field password lama wajib diisi jika password baru diisi
            'password_sekarang' => ['required_with:password_baru', 'string'],

            // password baru minimal 8 karakter jika diisi
            'password_baru' => ['nullable', 'string', 'min:8', 'confirmed'],
            // 'confirmed' otomatis mengharuskan ada field password_confirmation yang sama
        ];
    }

    // Validasi tambahan untuk cek password lama
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('password_baru')) {
                if (!Hash::check($this->input('password_sekarang'), $this->user()->password)) {
                    $validator->errors()->add('password_sekarang', 'Password saat ini tidak sesuai.');
                }
            }
        });
    }
}
